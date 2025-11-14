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
 * Class ApplicantCanSeeOverviewTest
 * 「本人」以外の申請者ロールでは、概要を表示できないことのテスト
 * @package Tests\Feature\Web\Controllers\Apply\Detail
 * @coversNothing
 */
class OtherApplicantCanNotSeeApplyInfoTest extends GetTestBase
{
    public function targetUrlsAndExpectedStatusCodeProvider(): array
    {
        return [
            ['/apply/detail/overview/1', 404],
            ['/apply/detail/section1/1', 404],
            ['/apply/detail/section2/1', 404],
            ['/apply/detail/section3/1', 404],
            ['/apply/detail/section4/1', 404],
            ['/apply/detail/section5/1', 404],
            ['/apply/detail/section6/1', 404],
            ['/apply/detail/section7/1', 404],
            ['/apply/detail/section8/1', 404],
            ['/apply/detail/section9/1', 404],
            ['/apply/detail/section10/1', 404],
            ['/apply/detail/overview/2', 404],
            ['/apply/detail/section1/2', 404],
            ['/apply/detail/section2/2', 404],
            ['/apply/detail/section3/2', 404],
            ['/apply/detail/section4/2', 404],
            ['/apply/detail/section5/2', 404],
            ['/apply/detail/section6/2', 404],
            ['/apply/detail/section7/2', 404],
            ['/apply/detail/section8/2', 404],
            ['/apply/detail/section9/2', 404],
            ['/apply/detail/section10/2', 404],

        ];
    }


    protected function getActor(): User
    {
        //申請者B
        return User::find(103);
    }
}
