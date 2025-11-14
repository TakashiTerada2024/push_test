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

namespace Tests\Feature\Http\Controllers\Attachment\Apply;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestBase;

/**
 * Class AddControllerTest
 * @package Tests\Feature\Http\Controllers\Attachment\Apply
 */
class AddControllerTest extends FeatureTestBase
{
    /**
     * test_OwnerCanAddAttachment
     * @covers \App\Http\Controllers\Attachment\Apply\AddController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OwnerCanAddAttachment()
    {
        //申請者であるユーザ101
        $ownerActor = User::find(101);
        $response = $this->actingAs($ownerActor)->post('/attachment/apply/add/1', []);

        //ステータスコードを検証
        $response->assertStatus(302);
        //添付ファイル表示画面へリダイレクトされることを検証
        $response->assertRedirect('/attachment/apply/show/1');
    }

    /**
     * test_SecretariatCanNotAddAttachment
     *
     * @covers \App\Http\Controllers\Attachment\Apply\AddController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_SecretariatCanNotAddAttachment()
    {
        //窓口側アカウント102
        $secretariatActor = User::find(102);
        $response = $this->actingAs($secretariatActor)->post('/attachment/apply/add/1', []);

        //ステータスコードを検証
        $response->assertStatus(403);
    }

    /**
     * test_OtherApplicantCanNotAddAttachment
     *
     * @covers \App\Http\Controllers\Attachment\Apply\AddController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OtherApplicantCanNotAddAttachment()
    {
        //申請者アカウント103、申請 1 の所有者ではない。
        $otherApplicantActor = User::find(103);
        $response = $this->actingAs($otherApplicantActor)->post('/attachment/apply/add/1', []);
        //ステータスコードを検証
        $response->assertStatus(403);
    }

    /**
     * test_AddAttachmentByOwner
     * 実際に添付ファイルを保存するテスト。
     *
     * @covers \App\Http\Controllers\Attachment\Apply\AddController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_AddAttachmentByOwner()
    {
        //ファイルアップロード用のfake
        $file = UploadedFile::fake()->image('testing_AddAttachmentByOwner.jpg');
        //申請者であるユーザ101
        $ownerActor = User::find(101);
        //パラメタとしてfakeで作成したファイルオブジェクトを指定
        $parameter = ['new' => $file];
        //リクエスト
        $response = $this->actingAs($ownerActor)->post('/attachment/apply/add/1', $parameter);
        //ステータスコードを検証
        $response->assertStatus(302);
        //添付ファイル表示画面へリダイレクトされることを検証
        $response->assertRedirect('/attachment/apply/show/1');
    }
}
