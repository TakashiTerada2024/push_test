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

use App\Models\Apply;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Tests\Feature\FeatureTestBase;

/**
 * Class OwnerCanNotModifyApplyAfterCheckingDocumentStatusTest
 * @package Http\Controllers\Apply\Detail
 */
class SecretariatCanNotModifyApplyTest extends FeatureTestBase
{
    /**
     * test
     * 申請のステータスに関係なく、事務局アカウントでのログイン時は申請情報の変更が許可されないことを検証する。
     * @param $url
     * @param $postParameters
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider targetUrlsAndExpectedStatusCodeProvider
     */
    public function test($url, $postParameters)
    {
        //preparations
        //ログインするユーザの作成
        $secretariat = User::factory()->state(['role_id' => 2])->create(); //role_id:2 secretariat account
        //申請の所有者ユーザの作成
        $applyOwner = User::factory()->state(['role_id' => 3])->create();

        $statuses = new ApplyStatuses();
        foreach ($statuses as $applyStatusId => $name) {
            //申請データの作成
            $targetApply = Apply::factory()->state([
                'user_id' => $applyOwner->id,
                'status' => $applyStatusId,
            ])->create();

            $path = $url . $targetApply->id;
            //テスト実行
            $response = $this->actingAs($secretariat)->post($path, $postParameters);
            //assertions
            $response->assertStatus(403);
        }
    }

    /**
     * targetUrlsAndExpectedStatusCodeProvider
     *
     * @return array[]
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function targetUrlsAndExpectedStatusCodeProvider(): array
    {
        return [
            //項目1
            ['/apply/detail/section1/', []],
            //項目2
            ['/apply/detail/section2/', []],
            //項目3
            ['/apply/detail/section3/', $this->dummyDataForSection03()],
            //項目4
            ['/apply/detail/section4/', []],
            //項目5
            ['/apply/detail/section5/', []],
            //項目6
            ['/apply/detail/section6/', []],
            //項目7
            ['/apply/detail/section7/', []],
            //項目8
            ['/apply/detail/section8/', []],
            //項目9
            ['/apply/detail/section9/', []],
            //項目10
            ['/apply/detail/section10/', []],

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
}
