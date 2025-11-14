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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSection2ToApplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->text('2_purpose_of_use')->nullable();
            $table->text('2_need_to_use')->nullable();
            $table->smallInteger('2_ethical_review_status')->nullable();
            $table->text('2_ethical_review_remark')->nullable();
            $table->text('2_ethical_review_board_name')->nullable();
            $table->text('2_ethical_review_board_code')->nullable();
            $table->date('2_ethical_review_board_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->dropColumn('2_purpose_of_use');
            $table->dropColumn('2_need_to_use');
            $table->dropColumn('2_ethical_review_status');
            $table->dropColumn('2_ethical_review_remark');
            $table->dropColumn('2_ethical_review_board_name');
            $table->dropColumn('2_ethical_review_board_code');
            $table->dropColumn('2_ethical_review_board_date');
        });
    }
}

