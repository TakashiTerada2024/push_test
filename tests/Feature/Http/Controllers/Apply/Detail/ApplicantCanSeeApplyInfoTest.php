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

namespace Tests\Feature\Http\Controllers\Apply\Detail;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ApplicantCanSeeApplyInfoTest
 * @package Tests\Feature\Http\Controllers\Apply\Detail
 * @coversNothing
 */
class ApplicantCanSeeApplyInfoTest extends GetTestBase
{
    public function targetUrlsAndExpectedStatusCodeProvider(): array
    {
        return [
            ['/apply/detail/overview/1', 200],
            ['/apply/detail/overview/2', 200],
            ['/apply/detail/overview/3', 200],
            ['/apply/detail/overview/4', 200],
            ['/apply/detail/section1/1', 200],
            ['/apply/detail/section1/2', 200],
            ['/apply/detail/section1/3', 200],
            ['/apply/detail/section1/4', 200],
            ['/apply/detail/section2/1', 200],
            ['/apply/detail/section2/2', 200],
            ['/apply/detail/section2/3', 200],
            ['/apply/detail/section2/4', 200],
            ['/apply/detail/section3/1', 200],
            ['/apply/detail/section3/2', 200],
            ['/apply/detail/section3/3', 200],
            ['/apply/detail/section3/4', 200],
            ['/apply/detail/section4/1', 200],
            ['/apply/detail/section4/2', 200],
            ['/apply/detail/section4/3', 200],
            ['/apply/detail/section4/4', 200],
            ['/apply/detail/section5/1', 200],
            ['/apply/detail/section5/2', 200],
            ['/apply/detail/section5/3', 200],
            ['/apply/detail/section5/4', 200],
            ['/apply/detail/section6/1', 200],
            ['/apply/detail/section6/2', 200],
            ['/apply/detail/section6/3', 200],
            ['/apply/detail/section6/4', 200],
            ['/apply/detail/section7/1', 200],
            ['/apply/detail/section7/2', 200],
            ['/apply/detail/section7/3', 200],
            ['/apply/detail/section7/4', 200],
            ['/apply/detail/section8/1', 200],
            ['/apply/detail/section8/2', 200],
            ['/apply/detail/section8/3', 200],
            ['/apply/detail/section8/4', 200],
            ['/apply/detail/section9/1', 200],
            ['/apply/detail/section9/2', 200],
            ['/apply/detail/section9/3', 200],
            ['/apply/detail/section9/4', 200],
            ['/apply/detail/section10/1', 200],
            ['/apply/detail/section10/2', 200],
            ['/apply/detail/section10/3', 200],
            ['/apply/detail/section10/4', 200],
        ];
    }

    protected function getActor(): User
    {
        return User::find(101);
    }
}
