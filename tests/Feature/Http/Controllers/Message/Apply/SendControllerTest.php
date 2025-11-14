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
use App\Notifications\CommonMessageNotificationToApplicant;
use App\Notifications\DatabaseNotificationOfApply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestBase;

class SendControllerTest extends FeatureTestBase
{
    /**
     * test_sendByApplyOwner
     *
     * @covers \App\Http\Controllers\Message\Apply\SendController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_sendByApplyOwner()
    {
        Notification::fake();
        //申請者であるユーザ101
        $applyOwnerActor = User::find(101);
        $parameter = ['message_body' => '本文'];
        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->post('/message/apply/send/1', $parameter);

        //Assertions
        //ステータスコードの検証
        $response->assertStatus(302);
        //リダイレクト先の検証
        $response->assertRedirect('/message/apply/show/1');
        //ログイン者が申請者権限である場合、通知が窓口組織全体を示す特殊なアカウント ID:2 に対して送信されるはずである。
        Notification::assertSentTo(User::find(2), DatabaseNotificationOfApply::class);
    }

    /**
     * test_sendBySecretariat
     *
     * @covers \App\Http\Controllers\Message\Apply\SendController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_sendBySecretariat()
    {
        Notification::fake();
        //窓口側アカウントであるユーザ102
        $applyOwnerActor = User::find(102);
        $parameter = ['message_body' => '本文'];
        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->post('/message/apply/send/1', $parameter);

        //Assertions
        //ステータスコードの検証
        $response->assertStatus(302);
        //リダイレクト先の検証
        $response->assertRedirect('/message/apply/show/1');
        //ログイン者が通知が申請の所有者（申請ID:1 の所有者であるUser:101）に対して送信されるはずである。
        Notification::assertSentTo(User::find(101), CommonMessageNotificationToApplicant::class);
    }

    /**
     * test_OtherApplicantCanNotSendMessage
     *
     * @covers \App\Http\Controllers\Message\Apply\SendController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OtherApplicantCanNotSendMessage()
    {
        Notification::fake();
        //申請の所有者ではないUser 103
        $applyOwnerActor = User::find(103);
        $parameter = ['message_body' => '本文'];
        //リクエスト
        $response = $this->actingAs($applyOwnerActor)->post('/message/apply/send/1', $parameter);

        //Assertions
        //ステータスコードの検証
        $response->assertStatus(403);
        //通知が送信されないことを検証
        Notification::assertNotSentTo(User::find(2), DatabaseNotificationOfApply::class);
    }
}
