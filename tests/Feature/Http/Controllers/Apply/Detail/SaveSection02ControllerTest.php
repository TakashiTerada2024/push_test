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
use Tests\Feature\FeatureTestBase;

class SaveSection02ControllerTest extends FeatureTestBase
{
    /**
     * test_申請者本人による正常系保存処理
     *
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @coversNothing
     */
    public function test_申請者本人による正常系保存処理()
    {
        //申請テストデータ3を対象にテストする。
        $urlForApply3 = '/apply/detail/section2/3';

        //TODO:DBへの保存処理を横から取って
        //以下、保存処理が正常に通るパラメータを適当に作成
        $parameters = [
            //17、21共通項目
            '2_purpose_of_use' => 'SaveSection02ControllerTest::test_正常系保存処理() 利用の目的',
            '2_need_to_use' => 'SaveSection02ControllerTest::test_正常系保存処理() 利用の必要性',

            //21
            '2_ethical_review_status' => 1,
            '2_ethical_review_remark' => 'SaveSection02ControllerTest::test_正常系保存処理() 倫理審査委員会',
            '2_ethical_review_board_name' => 'SaveSection02ControllerTest::test_正常系保存処理() 倫理審査委員会 名称',
            '2_ethical_review_board_code' => 'SaveSection02ControllerTest::test_正常系保存処理() 倫理審査委員会 承認番号',
            '2_ethical_review_board_date' => '2020-01-01',
        ];

        //
        $user101 = User::find(101);
        //いきなりPOSTして大丈夫？
        $response = $this->actingAs($user101)
            ->post($urlForApply3, $parameters);

        //リダイレクトされる
        $response->assertStatus(302);
        //保存処理後、表示画面へリダイレクトされるはずである。
        $response->assertRedirect($urlForApply3);
    }
}
