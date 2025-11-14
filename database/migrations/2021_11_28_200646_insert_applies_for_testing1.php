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

use App\Models\Apply;
use Illuminate\Database\Migrations\Migration;

class InsertAppliesForTesting1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //本番環境では以下のデータは不要
        if (config('app.env') === 'production') {
            return;
        }

        //テストデータの申請IDは1～1000まで、このマイグレーションではシーケンスは変更しない。
        /** @var Apply $model */
        $model = Apply::findOrNew(1);
        $model->id = 1;
        $model->user_id = 101;
        $model->type_id = 1;
        $model->affiliation = '所属1';
        $model->status = 2; //申出文書作成中
        $model->subject = 'テストデータ1/申出文書作成中（行政関係者・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '1/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(2);
        $model->id = 2;
        $model->user_id = 101;
        $model->type_id = 2;
        $model->affiliation = '所属2';
        $model->status = 2; //申出文書作成中
        $model->subject = 'テストデータ2/申出文書作成中（行政関係者・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '2/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(3);
        $model->id = 3;
        $model->user_id = 101;
        $model->type_id = 3;
        $model->affiliation = '所属3';
        $model->status = 2; //申出文書作成中
        $model->subject = 'テストデータ3/申出文書作成中（研究者等・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '3/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(4);
        $model->id = 4;
        $model->user_id = 101;
        $model->type_id = 4;
        $model->affiliation = '所属4';
        $model->status = 2; //申出文書作成中
        $model->subject = 'テストデータ4/申出文書作成中（研究者等・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '4/テスト用申請者アカウントA';
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //本番環境では以下のデータは不要
        if (config('app.env') === 'production') {
            return;
        }

        Apply::findOrNew(1)->delete();
        Apply::findOrNew(2)->delete();
        Apply::findOrNew(3)->delete();
        Apply::findOrNew(4)->delete();
    }
}
