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

class InsertAttachmentsForTesting1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('app.env') === 'production') {
            return;
        }
        //2023-10-12 feature/14272 において、テストデータ投入処理をすべて削除し、テスト側でデータを作るよう修正を行った。
        //この修正以前は、以下テスト用の添付資料データ（ID 1~15）を作成する処理が実行されていた。
        //このため、テスト用データと運用で実際に作成されるデータを分ける目的でシーケンスを1001番に設定する処理を行っていた。
        //feature/14272 の対応時、以下のシーケンス調整処理のみ残した。

        //シーケンス調整
        \Illuminate\Support\Facades\DB::statement("SELECT setval('attachments_id_seq',1001,false) ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
