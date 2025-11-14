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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace Tests\Feature\Http\Controllers\Pdf\Contents;

use App\Models\Apply;
use App\Models\User;
use App\Models\Attachment;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Tests\Feature\FeatureTestBase;

/**
 *  ScopeOfInformationUsedTest
 *
 * PDF出力内容テスト(4.利用する情報の範囲)
 *
 * @package Http\Controllers\Pdf
 */
class ScopeOfInformationUsedTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_診断年次
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param int|null $startYear
     * @param int|null $endYear
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_診断年次
     */
    public function test_download_診断年次(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        ?int $startYear,
        ?int $endYear
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_year_of_diagnose_start' => $startYear,
            '4_year_of_diagnose_end' => $endYear
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/<p class="pdl2">(.*)/', $contentsEachPage[4], $result);

        // Assertion
        // 4ページ目の診断年次が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s年次　～　%s年次/', $startYear, $endYear), $result[1]);
    }

    /**
     * nDataProvider_診断年次
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_診断年次()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_どちらも空白" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null],
            "申請者本人_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 2016, null],
            "申請者本人_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, null, 2017],
            "申請者本人_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, 2018, 2018],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_どちらも空白" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, null],
            "事務局_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, 2019, null],
            "事務局_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, null, 2020],
            "事務局_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, 2021, 2021],
        ];
    }

    /**
     * test_download_地域を選択していない場合はすべてチェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_地域を選択していない場合はすべてチェックしない
     */
    public function test_download_地域を選択していない場合はすべてチェックしない(
        int $actorIs,
        int $applyStatusId,
        int $applyType
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の地域にチェックマークが出力されていないこと
        $this->assertDoesNotMatchRegularExpression('/☑/', $targetTextList[1]);
    }

    /**
     * nDataProvider_地域を選択していない場合はすべてチェックしない
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_地域を選択していない場合はすべてチェックしない()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_どちらも空白" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            "申請者本人_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS],
            "申請者本人_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE],
            "申請者本人_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_どちらも空白" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            "事務局_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS],
            "事務局_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE],
            "事務局_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS],
        ];
    }

    /**
     * test_download_地域をすべて選択した場合は全国文言を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param string $prefectures
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_地域をすべて選択した場合は全国文言を出力
     */
    public function test_download_地域をすべて選択した場合は全国文言を出力(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        string $prefectures
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_area_prefectures' => $prefectures
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の地域にチェックされた全国が出力されていること
        $this->assertMatchesRegularExpression('/☑ 全国/', $targetTextList[1]);
    }

    /**
     * nDataProvider_地域をすべて選択した場合は全国文言を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_地域をすべて選択した場合は全国文言を出力()
    {
        $prefectures = "[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47]";
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_どちらも空白" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, $prefectures],
            "申請者本人_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, $prefectures],
            "申請者本人_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, $prefectures],
            "申請者本人_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, $prefectures],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_どちらも空白" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, $prefectures],
            "事務局_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, $prefectures],
            "事務局_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, $prefectures],
            "事務局_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, $prefectures],
        ];
    }

    /**
     * test_download_選択した地域をチェックして出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param string $prefectures
     * @param string $prefecture
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_選択した地域をチェックして出力
     */
    public function test_download_選択した地域をチェックして出力(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        string $prefectures,
        string $prefecture
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_area_prefectures' => $prefectures
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の対象地域がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/☑  %s/', $prefecture), $targetTextList[1]);
    }

    /**
     * nDataProvider_選択した地域をチェックして出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_選択した地域をチェックして出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_北海道" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "[1]", "北海道"],
            "申請者本人_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "[8]", "茨城県"],
            "申請者本人_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "[15]", "新潟県"],
            "申請者本人_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "[21]", "岐阜県"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_どちらも空白" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "[25]", "滋賀県"],
            "事務局_行政関係者・集計統計利用_開始年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "[31]", "鳥取県"],
            "事務局_研究者等・リンケージ利用_終了年のみ入力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "[36]", "徳島県"],
            "事務局_研究者等・集計統計利用_どちらも入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "[40]", "福岡県"],
        ];
    }

    /**
     * test_download_複数選択した地域をすべてチェックして出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param string $prefectures
     * @param $prefecture1
     * @param $prefecture2
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_複数選択した地域をすべてチェックして出力
     */
    public function test_download_複数選択した地域をすべてチェックして出力(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        string $prefectures,
        string $prefecture1,
        string $prefecture2
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_area_prefectures' => $prefectures
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の対象地域がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/☑  %s/', $prefecture1), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/☑  %s/', $prefecture2), $targetTextList[1]);
    }

    /**
     * nDataProvider_複数選択した地域をすべてチェックして出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_複数選択した地域をすべてチェックして出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_北海道" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "[2,3]", "青森県", "岩手県"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_どちらも空白" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "[9,10]", "栃木県", "群馬県"],
        ];
    }

    /**
     * test_download_がんの種類に選択した疾病分類を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int|null $idcType
     * @param string|null $exceptText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_がんの種類に選択した疾病分類を出力
     */
    public function test_download_がんの種類に選択した疾病分類を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?int $idcType,
        ?string $exceptText
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_idc_type' => $idcType
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の疾病分類が出力されていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptText), $targetTextList[2]);
    }

    /**
     * nDataProvider_がんの種類に選択した疾病分類を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_がんの種類に選択した疾病分類を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_ICD-10" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, 1, "ICD-10"],
            "申請者本人_行政関係者・集計統計利用_ICD-O-3" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 2, "ICD-O-3"],
            "申請者本人_研究者等・リンケージ利用_空欄" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, null, null],
            "申請者本人_研究者等・集計統計利用_対象外データ" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, 3, null],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_ICD-10" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 1, "ICD-10"],
            "事務局_行政関係者・集計統計利用_ICD-O-3" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, 2, "ICD-O-3"],
            "事務局_研究者等・リンケージ利用_空欄" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, null, null],
            "事務局_研究者等・集計統計利用_対象外データ" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, 3, null],
        ];
    }

    /**
     * test_download_疾病分類詳細を出力_改行なし
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param string|null $exceptText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_疾病分類詳細を出力_改行なし
     */
    public function test_download_疾病分類詳細を出力_改行なし(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        ?string $exceptText
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_idc_detail' => $exceptText
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の疾病分類詳細が出力されていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptText), $targetTextList[2]);
    }

    /**
     * nDataProvider_疾病分類詳細を出力_改行なし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_疾病分類詳細を出力_改行なし()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "疾病分類詳細テスト1"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "疾病分類詳細テスト2"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "疾病分類詳細テスト3"],
            "申請者本人_研究者等・集計統計利用_空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, null],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "疾病分類詳細テスト4"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "疾病分類詳細テスト5"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "疾病分類詳細テスト6"],
            "事務局_研究者等・集計統計利用_空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, null],
        ];
    }

    /**
     * test_download_疾病分類詳細を出力_改行あり
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $idcDetail
     * @param string $exceptText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_疾病分類詳細を出力_改行あり
     */
    public function test_download_疾病分類詳細を出力_改行あり(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $idcDetail,
        string $exceptText
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_idc_detail' => $idcDetail
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の疾病分類詳細が改行されていること
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $exceptText), $targetTextList[2]);
    }

    /**
     * nDataProvider_疾病分類詳細を出力_改行あり
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_疾病分類詳細を出力_改行あり()
    {
        $text = <<<EOT
        疾病分類詳細テスト%s
        改行テスト
        EOT;
        $exceptText = "疾病分類詳細テスト%s";

        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, sprintf($text, 1), sprintf($exceptText, 1)],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, sprintf($text, 2), sprintf($exceptText, 2)],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, sprintf($text, 3), sprintf($exceptText, 3)],
            "申請者本人_研究者等・集計統計利用_空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, sprintf($text, 4), sprintf($exceptText, 4)],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, sprintf($text, 5), sprintf($exceptText, 5)],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, sprintf($text, 6), sprintf($exceptText, 6)],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, sprintf($text, 7), sprintf($exceptText, 7)],
            "事務局_研究者等・集計統計利用_空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, sprintf($text, 8), sprintf($exceptText, 8)],
        ];
    }

    /**
     * test_download_生存確認情報が選択されている場合丸で囲んで出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param int|null $aliveRequired
     * @param int|null $aliveDateRequired
     * @param int|null $causeOfDeathRequired
     * @param array $exceptAliveRequired
     * @param array $exceptAliveDateRequired
     * @param array $exceptCauseOfDeathRequired
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_生存確認情報が選択されている場合丸で囲んで出力
     */
    public function test_download_生存確認情報が選択されている場合丸で囲んで出力(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        ?int $aliveRequired,
        ?int $aliveDateRequired,
        ?int $causeOfDeathRequired,
        array $exceptAliveRequired,
        array $exceptAliveDateRequired,
        array $exceptCauseOfDeathRequired
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_is_alive_required' => $aliveRequired,
            '4_is_alive_date_required' => $aliveDateRequired,
            '4_is_cause_of_death_required' => $causeOfDeathRequired
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<tr>/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の生存確認情報が選択した項目が丸で囲むクラスであること
        // ①生存しているか死亡しているかの別
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptAliveRequired["要"]), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptAliveRequired["不要"]), $targetTextList[1]);
        // ②生存を確認した直近の日又は死亡日
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptAliveDateRequired["要"]), $targetTextList[2]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptAliveDateRequired["不要"]), $targetTextList[2]);
        // ③死亡の原因
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptCauseOfDeathRequired["要"]), $targetTextList[3]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptCauseOfDeathRequired["不要"]), $targetTextList[3]);
    }

    /**
     * nDataProvider_生存確認情報が選択されている場合丸で囲んで出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_生存確認情報が選択されている場合丸で囲んで出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_すべて空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null, ['要' => '<span class="">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class="">不要<\/span>']],
            "申請者本人_行政関係者・集計統計利用_①だけ要" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 1, 2, 2, ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>']],
            "申請者本人_研究者等・リンケージ利用_②だけ要" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, 2, 1, 2, ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>']],
            "申請者本人_研究者等・集計統計利用_③だけ要" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, 2, 2, 1, ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>']],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_①だけ不要" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 2, 1, 1, ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>']],
            "事務局_行政関係者・集計統計利用_②だけ不要" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, 1, 2, 1, ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>']],
            "事務局_研究者等・リンケージ利用_③だけ不要" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 1, 1, 2, ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>']],
            "事務局_研究者等・集計統計利用_空欄_すべて要" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, 1, 1, 1, ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>'], ['要' => '<span class=" circle ">要<\/span>', '不要' => '<span class="">不要<\/span>']],
            "事務局_研究者等・集計統計利用_空欄_すべて不要" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, 2, 2, 2, ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>'], ['要' => '<span class="">要<\/span>', '不要' => '<span class=" circle ">不要<\/span>']],
        ];
    }

    /**
     * test_download_性別と備考を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param int|null $sex
     * @param string|null $sexText
     * @param string|null $sexDetail
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_性別と備考を出力
     */
    public function test_download_性別と備考を出力(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        ?int $sex,
        ?string $sexText,
        ?string $sexDetail
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_sex' => $sex,
            '4_sex_detail' => $sexDetail
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の性別と備考が出力されていること
        $this->assertMatchesRegularExpression(sprintf('/性別　%s/', $sexText), $targetTextList[4]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $sexDetail), $targetTextList[4]);
    }

    /**
     * nDataProvider_性別と備考を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_性別と備考を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用_性別空欄_備考空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null],
            "申請者本人_行政関係者・集計統計利用_男性のみ_備考空欄" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 1, "男性のみ", null],
            "申請者本人_研究者等・リンケージ利用_女性のみ_備考空欄" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, 2, "女性のみ", null],
            "申請者本人_研究者等・集計統計利用_両性別_備考空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, 3, "両性別", null],
            "申請者本人_行政関係者・リンケージ利用_性別想定外データ_備考空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, 4, null, null],

            // 事務局
            "事務局_行政関係者・リンケージ利用用_性別空欄_備考入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, null, "性別備考テスト1"],
            "事務局_行政関係者・集計統計利用_男性のみ_備考入力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, 1, "男性のみ", "性別備考テスト2"],
            "事務局_研究者等・リンケージ利用_女性のみ_備考入力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 2, "女性のみ", "性別備考テスト3"],
            "事務局_研究者等・集計統計利用_両性別_備考入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, 3, "両性別", "性別備考テスト4"],
            "事務局_行政関係者・リンケージ利用用_性別想定外データ_備考入力" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 4, null, "性別備考テスト5"],
        ];
    }

    /**
     * test_download_性別備考を出力_改行あり
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param string $sexDetail
     * @param string $exceptSexDetail
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_性別備考を出力_改行あり
     */
    public function test_download_性別備考を出力_改行あり(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        string $sexDetail,
        string $exceptSexDetail
    ) {
        // Preparations
        // Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            '4_sex_detail' => $sexDetail
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="mgt1 pdl1">/', $contentsEachPage[4]);

        // Assertion
        // 4ページ目の性別備考が改行されていること
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $exceptSexDetail), $targetTextList[4]);
    }

    /**
     * nDataProvider_性別備考を出力_改行あり
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_性別備考を出力_改行あり()
    {
        $text = <<<EOT
        性別備考テスト%s
        改行テスト
        EOT;
        $exceptText = "性別備考テスト%s";
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, sprintf($text, 1), sprintf($exceptText, 1)],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, sprintf($text, 2), sprintf($exceptText, 2)],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, sprintf($text, 3), sprintf($exceptText, 3)],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, sprintf($text, 4), sprintf($exceptText, 4)],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, sprintf($text, 5), sprintf($exceptText, 5)],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, sprintf($text, 6), sprintf($exceptText, 6)],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, sprintf($text, 7), sprintf($exceptText, 7)],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, sprintf($text, 8), sprintf($exceptText, 8)],
        ];
    }
}
