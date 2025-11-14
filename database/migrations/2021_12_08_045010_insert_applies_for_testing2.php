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

use Illuminate\Database\Migrations\Migration;
use App\Models\Apply;
class InsertAppliesForTesting2 extends Migration
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
        $model = Apply::findOrNew(5);
        $model->id = 5;
        $model->user_id = 101;
        $model->type_id = 1;
        $model->affiliation='所属5';
        $model->status = 1; //事前相談中
        $model->subject = 'テストデータ5/事前相談中（行政関係者・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '5/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(6);
        $model->id = 6;
        $model->user_id = 101;
        $model->type_id = 2;
        $model->affiliation='所属6';
        $model->status = 1; //事前相談中
        $model->subject = 'テストデータ6/事前相談中（行政関係者・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '6/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(7);
        $model->id = 7;
        $model->user_id = 101;
        $model->type_id = 3;
        $model->affiliation='所属7';
        $model->status = 1; //事前相談中
        $model->subject = 'テストデータ7/事前相談中（研究者等・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '7/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(8);
        $model->id = 8;
        $model->user_id = 101;
        $model->type_id = 4;
        $model->affiliation='所属8';
        $model->status = 1; //事前相談中
        $model->subject = 'テストデータ8/事前相談中（研究者等・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '8/テスト用申請者アカウントA';
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

        Apply::findOrNew(5)->delete();
        Apply::findOrNew(6)->delete();
        Apply::findOrNew(7)->delete();
        Apply::findOrNew(8)->delete();
    }
}
