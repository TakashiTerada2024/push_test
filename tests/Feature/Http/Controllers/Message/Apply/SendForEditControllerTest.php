<?php

/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace Tests\Feature\Http\Controllers\Message\Apply;

use App\Models\User;
//use App\Notifications\CommonMessageNotificationToApplicant;
use App\Notifications\DatabaseNotificationOfApply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Tests\Feature\FeatureTestBase;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Notifications\MessageNotificationForEditToApplicant;
use Illuminate\Support\Facades\App;
use Ncc01\Notification\Application\InputData\SendCommonMessageParameter;
use Ncc01\Notification\Application\Usecase\SendCommonMessageToApplicantInterface;
use App\Gateway\Repository\Messaging\MessageRepository;

class SendForEditControllerTest extends FeatureTestBase
{
    /**
     * test_sendForEditByApplyOwner
     *
     * @covers \App\Http\Controllers\Message\Apply\SendForEditController
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function test_sendForEditByApplyOwner()
    {
        //(1)まず更新元のメッセージを作成する
        $uuid = Str::uuid();
        $applyId = 1;
        $applyUserId = 101;//（申請ID:1 の所有者であるUser:101）

        $authUserId = 102;  //実行者 : 管理者であるユーザ
        $originalMessageUserId = 2;  //更新元メッセージ作成者 : 申請者であるユーザ(窓口組織全体を示す特殊なアカウント)

        $data = [
        'id' => $uuid,
        'notifiable_id' => $originalMessageUserId,
        'data' => '{"body":"TEST\n","fromId":' . $originalMessageUserId . ',"fromName":"\u4e8b\u52d9\u5c40\uff081\/\u30c6\u30b9\u30c8\u7528\u4e8b\u52d9\u5c40\u62c5\u5f53A\uff09"}',
        'apply_id' => $applyId,
        'parent_id' => null
        ];
        $this->insertNotification($data);

        //(2) (1)のメッセージに対して更新する。
        FacadesNotification::fake();
        $applyOwnerActor = User::find($authUserId);
        $query = '?messageBody=' . rawurlencode('本文update:' . $uuid);

        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->get('/message/apply/sendedit/' . $uuid . $query);

        //(3)Assertions
        //ステータスコードの検証
        $response->assertStatus(302);
        //リダイレクト先の検証
        $response->assertRedirect('/message/apply/show/' . $applyId);
        //ログイン者が通知が申請の所有者に対して送信されるはずである。
        FacadesNotification::assertSentTo(User::find($applyUserId), MessageNotificationForEditToApplicant::class);



        /* DB更新はキュー投入？で実行されていないので、普通にselectしても取れない。保留。
        $lastNotification = Notification::select('*')
            //->where('parent_id', $uuid)
            ->where('apply_id', $applyId)
            ->orderBy('created_at', 'DESC')->get();
        dump($lastNotification);*/
        /*$this->messageRepository = new MessageRepository();

        $message = $this->messageRepository->getMessageByNotificationId($uuid);
        */
    }

    /**
     * test_sendForEditByNotApplyOwner
     *
     * @covers \App\Http\Controllers\Message\Apply\SendForEditController
     * @author @author ushiro <k.ushiro@balocco.info>
     */
    public function test_sendForEditByNotApplyOwner()
    {
        //(1)まず更新元のメッセージを作成する
        $uuid = Str::uuid();
        $applyId = 1;
        $applyUserId = 101;//（申請ID:1 の所有者であるUser:101）

        $authUserId = 102;  //実行者 : 管理者であるユーザ
        $originalMessageUserId = 101;  //更新元メッセージ作成者 : 申請者であるユーザ

        $data = [
        'id' => $uuid,
        'notifiable_id' => $originalMessageUserId,
        'data' => '{"body":"TEST\n","fromId":' . $originalMessageUserId . ',"fromName":"1\/\u7533\u8acb\u8005A"}',
        'apply_id' => $applyId,
        'parent_id' => null
        ];
        $this->insertNotification($data);

        //(2) (1)のメッセージに対して更新する。
        FacadesNotification::fake();
        $applyOwnerActor = User::find($authUserId);
        $query = '?messageBody=' . rawurlencode('本文update:' . $uuid);

        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->get('/message/apply/sendedit/' . $uuid . $query);

        //(3)Assertions
        //ステータスコードの検証 : 申請者が作成したメッセージは更新不可
        $response->assertStatus(403);
        //通知が送信されないことを検証
        FacadesNotification::assertNotSentTo(User::find($applyUserId), MessageNotificationForEditToApplicant::class);
    }

    /**
     * test_sendForEditBySecretariat
     *
     * @covers \App\Http\Controllers\Message\Apply\SendForEditController
     * @author @author ushiro <k.ushiro@balocco.info>
     */
    public function test_sendForEditBySecretariat()
    {
        //(1)まず更新元のメッセージを作成する
        $uuid = Str::uuid();
        $applyId = 1;
        $applyUserId = 101;//（申請ID:1 の所有者であるUser:101）

        $authUserId = 101;  //実行者 : 申請者であるユーザ
        $originalMessageUserId = 101;  //更新元メッセージ作成者 : 申請者であるユーザ

        $data = [
        'id' => $uuid,
        'notifiable_id' => $originalMessageUserId,
        'data' => '{"body":"TEST\n","fromId":' . $originalMessageUserId . ',"fromName":"1\/\u7533\u8acb\u8005A"}',
        'read_at' => null,
        'apply_id' => $applyId,
        'parent_id' => null
        ];
        $this->insertNotification($data);

        //(2) (1)のメッセージに対して更新する。
        FacadesNotification::fake();
        $applyOwnerActor = User::find($authUserId);
        $query = '?messageBody=' . rawurlencode('本文update:' . $uuid);

        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->get('/message/apply/sendedit/' . $uuid . $query);

        //(3)Assertions
        //ステータスコードの検証 : 申請者は実行不可
        $response->assertStatus(403);
        //通知が送信されないことを検証
        FacadesNotification::assertNotSentTo(User::find($applyUserId), MessageNotificationForEditToApplicant::class);
    }

    /**
     * test_sendForEditToUpdatedRec
     *
     * @covers \App\Http\Controllers\Message\Apply\SendForEditController
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function test_sendForEditToUpdatedRec()
    {
        //(1)まず更新元のメッセージを作成する(更新レコード)
        $uuid = Str::uuid();
        $applyId = 1;
        $applyUserId = 101;//（申請ID:1 の所有者であるUser:101）

        $authUserId = 102;  //実行者 : 管理者であるユーザ
        $originalMessageUserId = 2;  //更新元メッセージ作成者 : 申請者であるユーザ(窓口組織全体を示す特殊なアカウント)

       /** @var Notification $model */
        $data = [
        'id' => $uuid,
        'notifiable_id' => $originalMessageUserId,
        'data' => '{"body":"TEST\n","fromId":' . $originalMessageUserId . ',"fromName":"\u4e8b\u52d9\u5c40\uff081\/\u30c6\u30b9\u30c8\u7528\u4e8b\u52d9\u5c40\u62c5\u5f53A\uff09"}',
        'apply_id' => $applyId,
        'parent_id' => Str::uuid()      //親レコードのID（ダミー）
        ];
        $this->insertNotification($data);

        //(2) (1)のメッセージに対して更新する。
        FacadesNotification::fake();
        $applyOwnerActor = User::find($authUserId);
        $query = '?messageBody=' . rawurlencode('本文update:' . $uuid);

        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->get('/message/apply/sendedit/' . $uuid . $query);

        //(3)Assertions
        //ステータスコードの検証 : 更新レコードに対しては更新不可
        $response->assertStatus(403);

        //通知が送信されないことを検証
        FacadesNotification::assertNotSentTo(User::find($applyUserId), MessageNotificationForEditToApplicant::class);
    }

        /**
     * test_sendForEditToDeletedRec
     *
     * @covers \App\Http\Controllers\Message\Apply\SendForEditController
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function test_sendForEditToDeletedRec()
    {
        //(1-1)まず更新元のメッセージを作成する(削除レコード)
        $uuid = Str::uuid();
        $applyId = 1;
        $applyUserId = 101;//（申請ID:1 の所有者であるUser:101）

        $authUserId = 102;  //実行者 : 管理者であるユーザ
        $originalMessageUserId = 2;  //更新元メッセージ作成者 : 申請者であるユーザ(窓口組織全体を示す特殊なアカウント)

        $data = [
        'id' => $uuid,
        'notifiable_id' => $originalMessageUserId,
        'data' => '{"body":"TEST","fromId":' . $originalMessageUserId . ',"fromName":"\u4e8b\u52d9\u5c40\uff081\/\u30c6\u30b9\u30c8\u7528\u4e8b\u52d9\u5c40\u62c5\u5f53A\uff09"}',
        'apply_id' => $applyId,
        'parent_id' => Str::uuid()      //親レコードのID（ダミー）
        ];
        $this->insertNotification($data);

        //(1-2) (1-1)のメッセージに対する削除レコードを作成する。
        $deleteRecId = Str::uuid();
        $data = [
            'id' => $deleteRecId,
            'notifiable_id' => $originalMessageUserId,
            'data' => '{"body":"' . __('apply.message.deleted') . '","fromId":' . $originalMessageUserId . ',"fromName":"\u4e8b\u52d9\u5c40\uff081\/\u30c6\u30b9\u30c8\u7528\u4e8b\u52d9\u5c40\u62c5\u5f53A\uff09"}',
            'apply_id' => $applyId,
            'parent_id' => $uuid      //親レコードのID
            ];
        $this->insertNotification($data);


        //(2) (1)のメッセージに対して更新する。
        FacadesNotification::fake();
        $applyOwnerActor = User::find($authUserId);
        $query = '?messageBody=' . rawurlencode('本文update:' . $uuid);

        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->get('/message/apply/sendedit/' . $uuid . $query);

        //(3)Assertions
        //ステータスコードの検証 : 削除状態レコードに対しては更新不可
        $response->assertStatus(403);

        //通知が送信されないことを検証
        FacadesNotification::assertNotSentTo(User::find($applyUserId), MessageNotificationForEditToApplicant::class);
    }

    private function insertNotification(array $data)
    {
        if (is_null($data['parent_id'])) {
            $data['type'] = 'App\Notifications\CommonMessageNotificationToApplicant';
        } else {
            $data['type'] = 'App\Notifications\MessageNotificationForEditToApplicant';
        }
        $data['notifiable_type'] = 'App\Models\User';
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $data['read_at'] = null;

        /** @var Notification $model */
        Notification::insert($data);
    }
}
