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

class AddSection4ToApplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->year('4_year_of_diagnose_start')->nullable();
            $table->year('4_year_of_diagnose_end')->nullable();
            $table->smallInteger('4_area_type')->nullable();
            $table->json('4_area_prefectures')->nullable();
            $table->smallInteger('4_idc_type')->nullable();
            $table->text('4_idc_detail')->nullable();
            $table->smallInteger('4_is_alive_required')->nullable();
            $table->smallInteger('4_is_alive_date_required')->nullable();
            $table->smallInteger('4_is_cause_of_death_required')->nullable();
            $table->smallInteger('4_sex')->nullable();
            $table->json('4_range_of_age')->nullable();
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
            $table->dropColumn('4_year_of_diagnose_start');
            $table->dropColumn('4_year_of_diagnose_end');
            $table->dropColumn('4_area_type');
            $table->dropColumn('4_area_prefectures');
            $table->dropColumn('4_idc_type');
            $table->dropColumn('4_idc_detail');
            $table->dropColumn('4_is_alive_required');
            $table->dropColumn('4_is_alive_date_required');
            $table->dropColumn('4_is_cause_of_death_required');
            $table->dropColumn('4_sex');
            $table->dropColumn('4_range_of_age');
        });
    }
}
