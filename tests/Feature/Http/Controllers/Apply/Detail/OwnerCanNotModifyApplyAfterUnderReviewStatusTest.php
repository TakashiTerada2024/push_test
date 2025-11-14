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
 * 2025-03-08仕様変更に伴い、テストクラス名を変更。
 * レビュー中以降のステータスである場合はドキュメント修正不可。
 * ※ロック状態による制御が追加され、確認中、提出中の場合も（ロック制御OFFであれば）修正可能となった。
 *
 * Class OwnerNotModifyApplyAfterCheckingDocumentStatusTest
 * @package Http\Controllers\Apply\Detail
 */
class OwnerCanNotModifyApplyAfterUnderReviewStatusTest extends FeatureTestBase
{
    /**
     * test
     * @dataProvider targetUrlsAndExpectedStatusCodeProvider
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test($url, $s, $postParameters)
    {
        //テスト用データの作成
        $applyOwner = User::factory()->state(['role_id' => 3])->create();

        //3:申出文書 確認中 以降のステータスの場合、変更は受け付けない
        $statuses = [ApplyStatuses::UNDER_REVIEW, ApplyStatuses::CANCEL, ApplyStatuses::ACCEPTED];
        foreach ($statuses as $applyStatusId) {
            $targetApply = Apply::factory()->state([
                'user_id' => $applyOwner->id,
                'status' => $applyStatusId,
            ])->create();

            $path = $url . $targetApply->id;
            //テスト実行
            $response = $this->actingAs($applyOwner)->post($path, $postParameters);
            //assertions
            $response->assertStatus(403);
        }
    }

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
            //項目1、種別が3である場合のみ保存処理のURLが有効になる。
            ['/apply/detail/section1/', 403, []],
            //項目2
            ['/apply/detail/section2/', 403, []],
            //項目3
            ['/apply/detail/section3/', 403, $this->dummyDataForSection03()],
            //項目4
            ['/apply/detail/section4/', 403, []],
            //項目5
            ['/apply/detail/section5/', 403, []],
            //項目6
            ['/apply/detail/section6/', 403, []],
            //項目7
            ['/apply/detail/section7/', 403, []],
            //項目8
            ['/apply/detail/section8/', 403, []],
            //項目9
            ['/apply/detail/section9/', 403, []],
            //項目10
            ['/apply/detail/section10/', 403, []],

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
