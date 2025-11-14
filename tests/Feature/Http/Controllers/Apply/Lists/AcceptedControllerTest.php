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

namespace Tests\Feature\Http\Controllers\Apply\Lists;

use App\Models\User;
use Tests\Feature\FeatureTestBase;

/**
 * Class AcceptedControllerTest
 *
 * @package Tests\Feature\Http\Controllers\Apply\Lists
 */
class AcceptedControllerTest extends FeatureTestBase
{
    /**
     * test_ApplicantUserCanNotSeeList
     *
     * @covers \App\Http\Controllers\Apply\Lists\AcceptedController
     * @author anhpd
     */
    public function test_ApplicantUserCanNotSeeList()
    {
        $applicantActor = User::find(101);
        $response = $this->actingAs($applicantActor)->get('/apply/lists/accepted');
        $response->assertStatus(403);

        $applicantActor = User::find(103);
        $response = $this->actingAs($applicantActor)->get('/apply/lists/accepted');
        $response->assertStatus(403);
    }

    /**
     * test_SecretariatUserCanSeeList
     * @covers \App\Http\Controllers\Apply\Lists\AcceptedController
     * @author anhpd
     */
    public function test_SecretariatUserCanSeeList()
    {
        $secretariatActor = User::find(102);
        $response = $this->actingAs($secretariatActor)->get('/apply/lists/accepted');
        $response->assertStatus(200);

        $secretariatActor = User::find(104);
        $response = $this->actingAs($secretariatActor)->get('/apply/lists/accepted');
        $response->assertStatus(200);
    }
}
