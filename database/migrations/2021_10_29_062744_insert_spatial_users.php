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

class InsertSpatialUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::transaction(
            function () {
                $model = new User();
                $model->id = 1;
                $model->name = 'システム管理者';
                $model->email = 'admin@balocco.info';
                $model->email_verified_at='2021-10-01 00:00:00';
                //実際にログインして利用する場合は各環境でパスワードを設定すること。
                $model->password = 'modify password to login';
                $model->role_id = 1;//システム管理者権限
                $model->save();

                $model = new User();
                $model->id = 2;
                $model->name = '事務局';
                $model->email = 'secretariat@balocco.info';
                //このアカウントはログイン不可
                $model->password = 'This account will never log in';
                $model->role_id = 2;//窓口組織
                $model->save();

                $model = new User();
                $model->id = 101;
                $model->name = 'テスト用申請者アカウントA';
                $model->email = 'test01@balocco.info';
                $model->email_verified_at='2021-10-01 00:00:00';
                $model->password = '$2y$10$UdxGlN0lkej/5SPyXDY7wuQJC8j/LXccjBAGPhOOg9Av1PS7NC1zq';
                $model->role_id = 3;//申出者
                $model->save();

                $model = new User();
                $model->id = 102;
                $model->name = 'テスト用事務局担当A';
                $model->email = 'test02@balocco.info';
                $model->email_verified_at='2021-10-01 00:00:00';
                $model->password = '$2y$10$UdxGlN0lkej/5SPyXDY7wuQJC8j/LXccjBAGPhOOg9Av1PS7NC1zq';
                $model->role_id = 2;//窓口組織
                $model->save();
            }
        );

        DB::statement("select setval('users_id_seq',1001,false)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::destroy([1, 2, 101, 102]);
    }
}
