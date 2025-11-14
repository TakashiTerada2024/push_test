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
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Tests\Feature\FeatureTestBase;

/**
 *  RegistrationInformationAndResearchMethodologyTest
 *
 * PDF出力内容テスト(5.利用する登録情報及び調査研究方法)
 *
 * @package Http\Controllers\Pdf
 */
class RegistrationInformationAndResearchMethodologyTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_様式例2ー1別紙がアップロードされていたらチェック
     *
     * 添付文書(様式例2ー1別紙)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例2ー1別紙がアップロードされていたらチェック
     */
    public function test_download_様式例2ー1別紙がアップロードされていたらチェック(
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

        // Attachment
        Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'attachment_type_id' => 501,// 様式例2-1別紙
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：様式例2-1号別紙/', $contentsEachPage[5], $result);

        // Assertion
        // 5ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_様式例2ー1別紙がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例2ー1別紙がアップロードされていたらチェック()
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
     * test_download_様式例2ー1別紙がアップロードされていない場合チェックなし
     *
     * 添付文書(様式例2ー1別紙)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例2ー1別紙がアップロードされていない場合チェックなし
     */
    public function test_download_様式例2ー1別紙がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：様式例2-1号別紙/', $contentsEachPage[5], $result);

        // Assertion
        // 5ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_様式例2ー1別紙がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例2ー1別紙がアップロードされていない場合チェックなし()
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
     * test_download_調査研究方法を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $exceptText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_調査研究方法を出力
     */
    public function test_download_調査研究方法を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
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
            '5_research_method' => $exceptText
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="pdl2">/', $contentsEachPage[5]);

        // Assertion
        // 5ページ目の調査研究方法が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptText), $targetTextList[1]);
    }

    /**
     * nDataProvider_調査研究方法を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_調査研究方法を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究方法テスト1"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "調査研究方法テスト2"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "調査研究方法テスト3"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "調査研究方法テスト4"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究方法テスト5"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "調査研究方法テスト6"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "調査研究方法テスト7"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "調査研究方法テスト8"],
        ];
    }

    /**
     * test_download_調査研究方法を出力_改行あり
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $researchMethod
     * @param string $exceptText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_調査研究方法を出力_改行あり
     */
    public function test_download_調査研究方法を出力_改行あり(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $researchMethod,
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
            '5_research_method' => $researchMethod
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/<p class="pdl2">/', $contentsEachPage[5]);

        // Assertion
        // 5ページ目の調査研究方法が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $exceptText), $targetTextList[1]);
    }

    /**
     * nDataProvider_調査研究方法を出力_改行あり
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_調査研究方法を出力_改行あり()
    {
        $text = <<<EOT
        調査研究方法テスト%s
        改行テスト
        EOT;
        $exceptText = "調査研究方法テスト%s";

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

    /**
     * test_download_集計表の様式案等がアップロードされていたらチェック
     *
     * 添付文書(集計表の様式案等)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_集計表の様式案等がアップロードされていたらチェック
     */
    public function test_download_集計表の様式案等がアップロードされていたらチェック(
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

        // Attachment
        Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'attachment_type_id' => 502,// 集計表の様式案等
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：集計表の様式案等/', $contentsEachPage[5], $result);

        // Assertion
        // 5ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_集計表の様式案等がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_集計表の様式案等がアップロードされていたらチェック()
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
     * test_download_集計表の様式案等がアップロードされていない場合チェックなし
     *
     * 添付文書(集計表の様式案等)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_集計表の様式案等がアップロードされていない場合チェックなし
     */
    public function test_download_集計表の様式案等がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：集計表の様式案等/', $contentsEachPage[5], $result);

        // Assertion
        // 5ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_集計表の様式案等がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_集計表の様式案等がアップロードされていない場合チェックなし()
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
}
