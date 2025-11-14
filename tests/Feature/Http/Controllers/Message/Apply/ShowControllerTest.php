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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\FeatureTestBase;

/**
 * Class ShowControllerTest
 * @package Tests\Feature\Http\Controllers\Message
 */
class ShowControllerTest extends FeatureTestBase
{
    /**
     * test_ApplicantCanSeeMessage
     *
     * @covers \App\Http\Controllers\Message\Apply\ShowController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_ApplicantCanSeeMessage()
    {
        //申請者であるユーザ101
        $ownerActor = User::find(101);
        $response = $this->actingAs($ownerActor)->get('/message/apply/show/1');
        $response->assertStatus(200);
    }

    /**
     * test_SecretariatCanDownloadFile
     *
     * @covers \App\Http\Controllers\Attachment\DownloadController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */

    /**
     * test_SecretariatCanSeeMessage
     *
     * @covers \App\Http\Controllers\Message\Apply\ShowController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_SecretariatCanSeeMessage()
    {
        //窓口組織アカウントであるユーザ102
        $secretariatActor = User::find(102);
        $response = $this->actingAs($secretariatActor)->get('/message/apply/show/1');
        $response->assertStatus(200);
    }

    /**
     * test_OtherUserCanNotSeeMessage
     *
     * @covers \App\Http\Controllers\Message\Apply\ShowController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OtherUserCanNotSeeMessage()
    {
        //申請者アカウント103、申請 1 の所有者ではない。
        $otherApplicantActor = User::find(103);
        $response = $this->actingAs($otherApplicantActor)->get('/message/apply/show/1');
        $response->assertStatus(403);
    }
}
