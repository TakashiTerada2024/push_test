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

/**
 * 申請者本人による申請情報の保存処理が成功することを検証する。
 * Class ApplicantCanSaveApplyInfoTest
 * @package Tests\Feature\Http\Controllers\Apply\Detail
 */
class ApplicantCanSaveApplyInfoTest extends PostTestBase
{
    /**
     * targetUrlsAndExpectedStatusCodeProvider
     * 保存処理のデータプロバイダ
     * 最低限、正常に保存が成功するパラメータを用意すれば良い。
     * @return array[]
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function targetUrlsAndExpectedStatusCodeProvider(): array
    {
        return [
            //項目1、種別が1、3である場合保存処理のURLが有効。
            ['/apply/detail/section1/1', 302, [], '/apply/detail/section1/1'],
            ['/apply/detail/section1/2', 404, []],
            ['/apply/detail/section1/3', 302, [], '/apply/detail/section1/3'],
            ['/apply/detail/section1/4', 404, []],
            //項目2
            ['/apply/detail/section2/1', 302, [], '/apply/detail/section2/1'],
            ['/apply/detail/section2/2', 302, [], '/apply/detail/section2/2'],
            ['/apply/detail/section2/3', 302, [], '/apply/detail/section2/3'],
            ['/apply/detail/section2/4', 302, [], '/apply/detail/section2/4'],
            //項目3、1-n 情報を保存する処理があり、最低1名分の情報入力が必要。
            ['/apply/detail/section3/1', 302, $this->dummyDataForSection03()],
            ['/apply/detail/section3/2', 302, $this->dummyDataForSection03()],
            ['/apply/detail/section3/3', 302, $this->dummyDataForSection03()],
            ['/apply/detail/section3/4', 302, $this->dummyDataForSection03()],
            //項目4
            ['/apply/detail/section4/1', 302, [], '/apply/detail/section4/1'],
            ['/apply/detail/section4/2', 302, [], '/apply/detail/section4/2'],
            ['/apply/detail/section4/3', 302, [], '/apply/detail/section4/3'],
            ['/apply/detail/section4/4', 302, [], '/apply/detail/section4/4'],
            //項目5
            ['/apply/detail/section5/1', 302, [], '/apply/detail/section5/1'],
            ['/apply/detail/section5/2', 302, [], '/apply/detail/section5/2'],
            ['/apply/detail/section5/3', 302, [], '/apply/detail/section5/3'],
            ['/apply/detail/section5/4', 302, [], '/apply/detail/section5/4'],
            //項目6
            ['/apply/detail/section6/1', 302, [], '/apply/detail/section6/1'],
            ['/apply/detail/section6/2', 302, [], '/apply/detail/section6/2'],
            ['/apply/detail/section6/3', 302, [], '/apply/detail/section6/3'],
            ['/apply/detail/section6/4', 302, [], '/apply/detail/section6/4'],
            //項目7
            ['/apply/detail/section7/1', 302, [], '/apply/detail/section7/1'],
            ['/apply/detail/section7/2', 302, [], '/apply/detail/section7/2'],
            ['/apply/detail/section7/3', 302, [], '/apply/detail/section7/3'],
            ['/apply/detail/section7/4', 302, [], '/apply/detail/section7/4'],
            //項目8
            ['/apply/detail/section8/1', 302, [], '/apply/detail/section8/1'],
            ['/apply/detail/section8/2', 302, [], '/apply/detail/section8/2'],
            ['/apply/detail/section8/3', 302, [], '/apply/detail/section8/3'],
            ['/apply/detail/section8/4', 302, [], '/apply/detail/section8/4'],
            //項目9
            ['/apply/detail/section9/1', 302, [], '/apply/detail/section9/1'],
            ['/apply/detail/section9/2', 302, [], '/apply/detail/section9/2'],
            ['/apply/detail/section9/3', 302, [], '/apply/detail/section9/3'],
            ['/apply/detail/section9/4', 302, [], '/apply/detail/section9/4'],
            //項目10
            ['/apply/detail/section10/1', 302, [], '/apply/detail/section10/1'],
            ['/apply/detail/section10/2', 302, [], '/apply/detail/section10/2'],
            ['/apply/detail/section10/3', 302, [], '/apply/detail/section10/3'],
            ['/apply/detail/section10/4', 302, [], '/apply/detail/section10/4'],
        ];
    }

    public function dummyDataForSection03(): array
    {
        return [
            '3_number_of_users' => 1,
            'apply_users' => [
                0 => [
                    'name' => '利用者1の氏名',
                    'institution' => '利用者1の所属機関',
                    'position' => '利用者1の職名',
                    'role' => '利用者1の役割'
                ]
            ]
        ];
    }

    protected function getActor(): User
    {
        return User::find(101);
    }
}
