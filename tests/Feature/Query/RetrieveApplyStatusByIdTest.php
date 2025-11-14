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

namespace Tests\Feature\Query;

use App\Models\Apply;
use App\Query\RetrieveApplyStatusById;
use Tests\Feature\FeatureTestBase;

class RetrieveApplyStatusByIdTest extends FeatureTestBase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_retrieve_apply_status_by_id()
    {
        /** @var Apply $model */
        $model = Apply::findOrNew(1111);
        $model->id = 1111;
        $model->user_id = 101;
        $model->type_id = 1;
        $model->affiliation = '所属13';
        $model->status = 4; //申出文書提出中
        $model->subject = 'テストデータ13/申出文書提出中（行政関係者・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '13/テスト用申請者アカウントA';
        $model->save();

        $retrieveApplyStatusById = new RetrieveApplyStatusById();

        $this->assertEquals($model->status, $retrieveApplyStatusById->__invoke(1111));

        $max = Apply::max('id');
        $this->assertEquals(0, $retrieveApplyStatusById->__invoke($max + 1));

        Apply::findOrNew(1111)->delete();
    }
}
