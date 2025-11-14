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

class InsertUserInProduction extends Migration
{
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

        //事務局メンバーの追加登録
        /** @var User $model */
        $model = User::findOrNew(506);
        $model->id = 506;
        $model->name = '梅沢　淳';
        $model->email = 'jumezawa@ncc.go.jp';
        $model->email_verified_at = '2022-06-10 15:00:00';
        $model->password = '$2y$10$jJu.tjm4TRtMoOVM.TdQ..GR5BnplZLxZz22xld8exUrcatgZJZFe';
        $model->role_id = 2;
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