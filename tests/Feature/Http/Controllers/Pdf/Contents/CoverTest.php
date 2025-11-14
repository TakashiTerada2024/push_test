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
use App\Models\ApplyHistory;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Tests\Feature\FeatureTestBase;

/**
 *  CoverTest
 *
 * PDF出力内容テスト(かがみ)
 *
 * @package Http\Controllers\Pdf
 */
class CoverTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_申出種別に応じて様式名を表示
     *
     * 申出種別に応じて様式名を変更
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $styleName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出種別に応じて様式名を表示
     */
    public function test_download_申出種別に応じて様式名を表示(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $styleName
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

        // Assertion
        // 1ページ目の様式名が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $styleName), $contentsEachPage[1]);
    }

    /**
     * nDataProvider_申出種別に応じて様式名を表示
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出種別に応じて様式名を表示()
    {
        return [
            // 申請者本人
            // 行政関係者・リンケージ利用
            "申請者本人_行政関係者・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],
            "申請者本人_行政関係者・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],
            "申請者本人_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],

            // 行政関係者・集計統計利用
            "申請者本人_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],
            "申請者本人_行政関係者・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],
            "申請者本人_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],

            // 研究者等・リンケージ利用
            "申請者本人_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],
            "申請者本人_研究者等・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],
            "申請者本人_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],

            // 研究者等・集計統計利用
            "申請者本人_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],
            "申請者本人_研究者等・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],
            "申請者本人_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],

            // 事務局
            // 行政関係者・リンケージ利用
            "事務局_行政関係者・リンケージ利用用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],
            "事務局_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],
            "事務局_行政関係者・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE, '様式第2_1申出17条'],

            // 行政関係者・集計統計利用
            "事務局_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],
            "事務局_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],
            "事務局_行政関係者・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_STATISTICS, '様式第2_1申出17条'],

            // 研究者等・リンケージ利用
            "事務局_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],
            "事務局_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],
            "事務局_研究者等・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '様式第2-1号申出21_3'],

            // 研究者等・集計統計利用
            "事務局_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],
            "事務局_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],
            "事務局_研究者等・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_STATISTICS, '様式第2-1号申出21_4'],
        ];
    }

    /**
     * test_download_提出日
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $submittedAt
     * @param string $filingDate
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_提出日
     */
    public function test_download_提出日(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $submittedAt,
        string $filingDate
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
            'submitted_at' => $submittedAt
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());

        // Assertion
        // 1ページ目の提出日が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $filingDate), $contentsEachPage[1]);
    }

    /**
     * nDataProvider_提出日
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_提出日()
    {
        $timestapm = '2023/07/03 0:00:00';
        $date = '2023年07月03日';
        return [
            // 申請者本人
            "申請者本人_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）'],
            "申請者本人_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）'],
            "申請者本人_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date],

            // 事務局
            "事務局_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）'],
            "事務局_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date],
            "事務局_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date],
        ];
    }

    /**
     * test_download_変更履歴がある場合は提出日に追加で出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $submittedAt
     * @param string $filingDate
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_変更履歴がある場合は提出日に追加で出力
     */
    public function test_download_変更履歴がある場合は提出日に追加で出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $submittedAt,
        string $filingDate,
        ?string $beforeChangeSubmittedAt,
        ?string $beforeChangeFilingDate
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

        // 変更履歴Apply
        $beforeChangeApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => 20,
            'type_id' => $applyType,
            'submitted_at' => $beforeChangeSubmittedAt
        ])->create();

        // Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
            'submitted_at' => $submittedAt
        ])->create();

        // ApplyHistory
        ApplyHistory::factory()->state([
            'apply_id' => $targetApply->id,
            'source_apply_id' => $beforeChangeApply->id,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());

        // Assertion
        // 1ページ目の提出日が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $filingDate), $contentsEachPage[1]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $beforeChangeFilingDate), $contentsEachPage[1]);
    }

    /**
     * nDataProvider_変更履歴がある場合は提出日に追加で出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_変更履歴がある場合は提出日に追加で出力()
    {
        $timestapm = '2023/07/03 0:00:00';
        $date = '2023年07月03日';
        $beforeChangeTimestapm = '2023/07/01 0:00:00';
        $beforeChangeDate = '2023年07月01日';
        return [
            // 申請者本人
            "申請者本人_どちらも提出日なし" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）', null, '（提出日:未定）'],
            "申請者本人_変更履歴の提出日のみあり" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）', $beforeChangeTimestapm, $beforeChangeDate],
            "申請者本人_どちらも提出日あり" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date, $beforeChangeTimestapm, $beforeChangeDate],

            // 事務局
            "事務局_どちらも提出日なし" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, '（提出日:未定）', null, '（提出日:未定）'],
            "事務局_現在の提出日のみあり" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date, null, '（提出日:未定）'],
            "事務局_どちらも提出日あり" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE, $timestapm, $date, $beforeChangeTimestapm, $beforeChangeDate],
        ];
    }

    /**
     * test_download_申出種別に応じた宛先を出力
     *
     * 申出種別に応じて宛先が異なること
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $styleName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出種別に応じた宛先を出力
     */
    public function test_download_申出種別に応じた宛先を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $styleName
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

        // Assertion
        // 1ページ目の宛先が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $styleName), $contentsEachPage[1]);
    }

    /**
     * nDataProvider_申出種別に応じた宛先を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出種別に応じた宛先を出力()
    {
        return [
            // 申請者本人
            // 行政関係者・リンケージ利用
            "申請者本人_行政関係者・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],
            "申請者本人_行政関係者・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],
            "申請者本人_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],

            // 行政関係者・集計統計利用
            "申請者本人_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "申請者本人_行政関係者・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "申請者本人_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],

            // 研究者等・リンケージ利用
            "申請者本人_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],
            "申請者本人_研究者等・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],
            "申請者本人_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],

            // 研究者等・集計統計利用
            "申請者本人_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "申請者本人_研究者等・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "申請者本人_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],

            // 事務局
            // 行政関係者・リンケージ利用
            "事務局_行政関係者・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],
            "事務局_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],
            "事務局_行政関係者・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE, '厚生労働大臣 殿'],

            // 行政関係者・集計統計利用
            "事務局_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "事務局_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "事務局_行政関係者・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],

            // 研究者等・リンケージ利用
            "事務局_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],
            "事務局_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],
            "事務局_研究者等・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '厚生労働大臣 殿'],

            // 研究者等・集計統計利用
            "事務局_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "事務局_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
            "事務局_研究者等・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_STATISTICS, '国立研究開発法人\<br \/\>国立がん研究センター 理事長 殿'],
        ];
    }

    /**
     * test_download_申出者情報
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $affiliation
     * @param string $applicantName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出者情報
     */
    public function test_download_申出者情報(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $affiliation,
        string $applicantName
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
            'affiliation' => $affiliation,
            '10_applicant_name' => $applicantName
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());

        // Assertion
        // 1ページ目の申出者情報が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s\<br \/\>/', $affiliation), $contentsEachPage[1]);
        $this->assertMatchesRegularExpression(sprintf('/%s\<br \/\>/', $applicantName), $contentsEachPage[1]);
    }

    /**
     * nDataProvider_申出者情報
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出者情報()
    {
        return [
            // 申請者本人
            "申請者本人_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, 'テスト所属', 'テスト名'],

            // 事務局
            "事務局_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 'テスト所属', 'テスト名'],
        ];
    }

    /**
     * test_download_申出種別に応じた申出件名を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出種別に応じた申出件名を出力
     */
    public function test_download_申出種別に応じた申出件名を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $expectSubject,
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
            'type_id' => $applyType
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/\<p class="t-center mgt3"\>(.*)/', $contentsEachPage[1], $subject);

        // Assertion
        // 1ページ目の申出件名が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectSubject), $subject[1]);
    }

    /**
     * nDataProvider_申出種別に応じた申出件名を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出種別に応じた申出件名を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報の提供について（申出）'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報の提供について（申出）'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報の提供について（申出）'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報の提供について（申出）'],

            // 事務局
            "事務局_行政関係者・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報の提供について（申出）'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報の提供について（申出）'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報の提供について（申出）'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報の提供について（申出）'],
        ];
    }

    /**
     * test_download_申出概要
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出概要
     */
    public function test_download_申出概要(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $expectSubject,
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
            'type_id' => $applyType
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/\<p class="mgt5"\>(.*)/', $contentsEachPage[1], $summary);

        // Assertion
        // 1ページ目の申出概要が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectSubject), $summary[1]);
    }

    /**
     * nDataProvider_申出概要
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出概要()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）（17条、第21条第1項、第21条第2項）の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第17条の規定に基づき、別紙のとおり匿名化が行われた全国がん登録情報の提供の申出を行います。'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第3項の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第4項の規定に基づき、別紙のとおり匿名化が行われた全国がん登録情報の提供の申出を行います。'],

            // 事務局
            "事務局_行政関係者・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）（17条、第21条第1項、第21条第2項）の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第17条の規定に基づき、別紙のとおり匿名化が行われた全国がん登録情報の提供の申出を行います。'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第3項の規定に基づき、別紙のとおり全国がん登録情報の提供の申出を行います。'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '標記について、がん登録等の推進に関する法律（平成25年法律第111号）第21条第4項の規定に基づき、別紙のとおり匿名化が行われた全国がん登録情報の提供の申出を行います。'],
        ];
    }

    /**
     * test_download_設定されたカスタム鏡文を申出概要に出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_設定されたカスタム鏡文を申出概要に出力
     */
    public function test_download_設定されたカスタム鏡文を申出概要に出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $expectSummary,
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
            'summary' => $expectSummary
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/\<p class="mgt5"\>(.*)/', $contentsEachPage[1], $summary);

        // Assertion
        // 1ページ目のカスタム鏡文と一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectSummary), $summary[1]);
    }

    /**
     * nDataProvider_設定されたカスタム鏡文を申出概要に出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_設定されたカスタム鏡文を申出概要に出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, 'テスト文言'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 'テスト文言'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, 'テスト文言'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, 'テスト文言'],

            // 事務局
            "事務局_行政関係者・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 'テスト文言'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, 'テスト文言'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 'テスト文言'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, 'テスト文言'],
        ];
    }
}
