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

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (config('app.env') !== 'production') {
            return;
        }

        //事務局メンバーの初期登録
        /** @var User $model */
        $model = User::findOrNew(501);
        $model->id = 501;
        $model->name = '東　尚弘';
        $model->email = 'thigashi@ncc.go.jp';
        $model->email_verified_at = '2022-02-25 13:30:01';
        $model->password = '$2y$10$BHd7yE2XQejMpOhfT1HP1OWmCs.Hi138riRDJXO4pwimyiLsKRP8i';
        $model->role_id = 2;
        $model->save();

        /** @var User $model */
        $model = User::findOrNew(502);
        $model->id = 502;
        $model->name = '藤下　真奈美';
        $model->email = 'mafujish@ncc.go.jp';
        $model->email_verified_at = '2022-02-25 13:30:02';
        $model->password = '$2y$10$ta9XadHS6KY3Jk9jMPPSEOpvFSHkS4dJeB1.KJ3cTZDjIOrnl4tpy';
        $model->role_id = 2;
        $model->save();

        /** @var User $model */
        $model = User::findOrNew(503);
        $model->id = 503;
        $model->name = '松浦　志保';
        $model->email = 'shmatsuu@ncc.go.jp';
        $model->email_verified_at = '2022-02-25 13:30:03';
        $model->password = '$2y$10$QRF0dLKddFY8KWBQao2sGejt7NwJGb1mJt26NiQLgT3/X8QTg82RO';
        $model->role_id = 2;
        $model->save();

        /** @var User $model */
        $model = User::findOrNew(504);
        $model->id = 504;
        $model->name = '小林　佳代子';
        $model->email = 'kaykobay@ncc.go.jp';
        $model->email_verified_at = '2022-02-25 13:30:04';
        $model->password = '$2y$10$wMoDvmpC3vkyzP6PzDQOPuAenn9X6heAedZeEY3lKqq9RCPJNit0m';
        $model->role_id = 2;
        $model->save();

        /** @var User $model */
        $model = User::findOrNew(505);
        $model->id = 505;
        $model->name = '榊原　直喜';
        $model->email = 'nsakakib@ncc.go.jp';
        $model->email_verified_at = '2022-02-25 13:30:05';
        $model->password = '$2y$10$BI5QL1ush2WwE2ZCe8foiOzYtTgtG5yH5z2B..CjqHAOrKbTCk9Pe';
        $model->role_id = 2;
        $model->save();

        //事務局メールアドレス
        $model = User::find(2);
        $model->email='ncr_datause@ml.res.ncc.go.jp';
        $model->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('app.env') !== 'production') {
            return;
        }
    }
};
