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

class InsertAppliesForTesting4 extends Migration
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

        /** @var Apply $model */
        $model = Apply::findOrNew(13);
        $model->id = 13;
        $model->user_id = 101;
        $model->type_id = 1;
        $model->affiliation = '所属13';
        $model->status = 4; //申出文書提出中
        $model->subject = 'テストデータ13/申出文書提出中（行政関係者・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '13/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(14);
        $model->id = 14;
        $model->user_id = 101;
        $model->type_id = 2;
        $model->affiliation = '所属14';
        $model->status = 4; //申出文書提出中
        $model->subject = 'テストデータ14/申出文書提出中（行政関係者・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '14/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(15);
        $model->id = 15;
        $model->user_id = 101;
        $model->type_id = 3;
        $model->affiliation = '所属15';
        $model->status = 4; //申出文書確認中
        $model->subject = 'テストデータ15/申出文書提出中（研究者等・リンケージ利用）研究タイトル';
        $model->{'10_applicant_name'} = '15/テスト用申請者アカウントA';
        $model->save();

        /** @var Apply $model */
        $model = Apply::findOrNew(16);
        $model->id = 16;
        $model->user_id = 101;
        $model->type_id = 4;
        $model->affiliation = '所属16';
        $model->status = 4; //申出文書提出中
        $model->subject = 'テストデータ16/事前相談中（研究者等・集計統計利用）研究タイトル';
        $model->{'10_applicant_name'} = '16/テスト用申請者アカウントA';
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

        Apply::findOrNew(13)->delete();
        Apply::findOrNew(14)->delete();
        Apply::findOrNew(15)->delete();
        Apply::findOrNew(16)->delete();
    }
}
