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
 *  PeriodOfUseTest
 *
 * PDF出力内容テスト(6.利用期間)
 *
 * @package Http\Controllers\Pdf
 */
class PeriodOfUseTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_始期の文言を固定表示
     *
     * 「情報の提供を受けた日」を固定表示
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_始期の文言を固定表示
     */
    public function test_download_始期の文言を固定表示(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType
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
        preg_match('/始期　(.*)/', $contentsEachPage[6], $result);

        // Assertion
        // 6ページ目の始期の文言が一致していること
        $this->assertMatchesRegularExpression('/情報の提供を受けた日/', $result[1]);
    }

    /**
     * nDataProvider_始期の文言を固定表示
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_始期の文言を固定表示()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS],
        ];
    }

    /**
     * test_download_始期の文言を固定表示_DBの値が影響しないこと
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $usagePeriodStart
     * @param string $ResearchPeriodStart
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_始期の文言を固定表示_DBの値が影響しないこと
     */
    public function test_download_始期の文言を固定表示_DBの値が影響しないこと(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $usagePeriodStart,
        string $ResearchPeriodStart
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
            '6_usage_period_start' => $usagePeriodStart,
            '6_research_period_start' => $ResearchPeriodStart
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/始期　(.*)/', $contentsEachPage[6], $result);

        // Assertion
        // 6ページ目の始期の文言が一致していること
        $this->assertMatchesRegularExpression('/情報の提供を受けた日/', $result[1]);
    }

    /**
     * nDataProvider_始期の文言を固定表示_DBの値が影響しないこと
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_始期の文言を固定表示_DBの値が影響しないこと()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '2023-06-01', '2023-07-01'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '2023-06-02', '2023-07-02'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '2023-06-03', '2023-07-03'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '2023-06-04', '2023-07-04'],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '2023-06-05', '2023-07-05'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '2023-06-06', '2023-07-06'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '2023-06-07', '2023-07-07'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '2023-06-08', '2023-07-08'],
        ];
    }

    /**
     * test_download_終期を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string|null $usagePeriodEnd
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_終期を出力
     */
    public function test_download_終期を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $usagePeriodEnd
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
            '6_usage_period_end' => $usagePeriodEnd
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/終期　(.*)/', $contentsEachPage[6], $result);

        // Assertion
        // 6ページ目の終期の文言が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $usagePeriodEnd), $result[1]);
    }

    /**
     * nDataProvider_終期を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_終期を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '2023-07-01'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '2023-07-02'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '2023-07-03'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '2023-07-04'],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '2023-07-05'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '2023-07-06'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '2023-07-07'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, null],
        ];
    }

    /**
     * test_download_終期を出力_6_research_period_endが影響しないこと
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string|null $usagePeriodEnd
     * @param string|null $researchPeriodEnd
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_終期を出力_6_research_period_endが影響しないこと
     */
    public function test_download_終期を出力_6_research_period_endが影響しないこと(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $usagePeriodEnd,
        ?string $researchPeriodEnd
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
            '6_usage_period_end' => $usagePeriodEnd,
            '6_research_period_end' => $researchPeriodEnd
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/終期　(.*)/', $contentsEachPage[6], $result);

        // Assertion
        // 6ページ目の終期の文言が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $usagePeriodEnd), $result[1]);
    }

    /**
     * nDataProvider_終期を出力_6_research_period_endが影響しないこと
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_終期を出力_6_research_period_endが影響しないこと()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '2023-07-01', '2023-06-01'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '2023-07-02', '2023-06-02'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '2023-07-03', '2023-06-03'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '2023-07-04', '2023-06-04'],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '2023-07-05', '2023-06-05'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, null, '2023-06-06'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '2023-07-07', null],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, null, null],
        ];
    }
}
