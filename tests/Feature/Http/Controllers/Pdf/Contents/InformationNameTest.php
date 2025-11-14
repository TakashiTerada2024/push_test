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
 *  InformationNameTest
 *
 * PDF出力内容テスト(1.申出に係る情報の名称)
 *
 * @package Http\Controllers\Pdf
 */
class InformationNameTest extends FeatureTestBase
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
     * @param string $expectName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出種別に応じて様式名を表示
     */
    public function test_download_申出種別に応じて様式名を表示(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $expectName
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
        preg_match('/\<p class="t-center"\>(.*)/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の様式名が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectName), $result[1]);
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
     * test_download_申出種別に応じて申出に係る情報の名称を出力
     *
     * 申出種別に応じて「申出に係る情報の名称」を変更
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $expectName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_申出種別に応じて申出に係る情報の名称を出力
     */
    public function test_download_申出種別に応じて申出に係る情報の名称を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $expectName
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
        preg_match('/\<p class="pdl2 t-bold"\>(.*)/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の「申出に係る情報の名称」が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectName), $result[1]);
    }

    /**
     * nDataProvider_申出種別に応じて申出に係る情報の名称を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_申出種別に応じて申出に係る情報の名称を出力()
    {
        return [
            // 申請者本人
            // 行政関係者・リンケージ利用
            "申請者本人_行政関係者・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "申請者本人_行政関係者・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "申請者本人_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],

            // 行政関係者・集計統計利用
            "申請者本人_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "申請者本人_行政関係者・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "申請者本人_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],

            // 研究者等・リンケージ利用
            "申請者本人_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "申請者本人_研究者等・リンケージ利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "申請者本人_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],

            // 研究者等・集計統計利用
            "申請者本人_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "申請者本人_研究者等・集計統計利用_申出文書確認中" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "申請者本人_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],

            // 事務局
            // 行政関係者・リンケージ利用
            "事務局_行政関係者・リンケージ利用用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "事務局_行政関係者・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "事務局_行政関係者・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE, '全国がん登録情報（非匿名化情報）'],

            // 行政関係者・集計統計利用
            "事務局_行政関係者・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "事務局_行政関係者・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "事務局_行政関係者・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_STATISTICS, '匿名化が行われた全国がん登録情報'],

            // 研究者等・リンケージ利用
            "事務局_研究者等・リンケージ利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "事務局_研究者等・リンケージ利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],
            "事務局_研究者等・リンケージ利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '全国がん登録情報（非匿名化情報）'],

            // 研究者等・集計統計利用
            "事務局_研究者等・集計統計利用_申出文書作成中" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "事務局_研究者等・集計統計利用_申出文書提出中" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],
            "事務局_研究者等・集計統計利用_応諾" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_STATISTICS, '匿名化が行われた全国がん登録情報'],
        ];
    }

    /**
     * test_download_添付文書当該研究に係る同意取得説明文書がアップロードされていたらチェック
     *
     * 添付文書(添付文書当該研究に係る同意取得説明文書)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていたらチェック
     */
    public function test_download_添付文書当該研究に係る同意取得説明文書がアップロードされていたらチェック(
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
            'attachment_type_id' => 101,// 同意書等
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：当該研究に係る同意取得説明文書/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE,],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS],
        ];
    }

    /**
     * test_download_添付文書当該研究に係る同意取得説明文書がアップロードされていない場合チェックなし
     *
     * 添付文書(添付文書当該研究に係る同意取得説明文書)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていない場合チェックなし
     */
    public function test_download_添付文書当該研究に係る同意取得説明文書がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：当該研究に係る同意取得説明文書/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_添付文書当該研究に係る同意取得説明文書がアップロードされていない場合チェックなし()
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
     * test_download_様式例第3ー2号等がアップロードされていたらチェック
     *
     * 添付文書(様式例第3-2号等)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第3ー2号等がアップロードされていたらチェック
     */
    public function test_download_様式例第3ー2号等がアップロードされていたらチェック(
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
            'attachment_type_id' => 102,// 様式第3-2号等
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：様式例第3-2号等（該当時）/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_様式例第3ー2号等がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第3ー2号等がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE,],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS],
        ];
    }

    /**
     * test_download_様式例第3ー2号等がアップロードされていない場合チェックなし
     *
     * 添付文書(様式例第3ー2号等)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第3ー2号等がアップロードされていない場合チェックなし
     */
    public function test_download_様式例第3ー2号等がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：様式例第3-2号等（該当時）/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_様式例第3ー2号等がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第3ー2号等がアップロードされていない場合チェックなし()
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
     * test_download_実績を示す論文、報告書等等がアップロードされていたらチェック
     *
     * 添付文書(実績を示す論文、報告書等)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_実績を示す論文、報告書等がアップロードされていたらチェック
     */
    public function test_download_実績を示す論文、報告書等がアップロードされていたらチェック(
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
            'attachment_type_id' => 103,// 実績を示す論文、報告書等
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：実績を示す論文・報告書等/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_実績を示す論文、報告書等がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_実績を示す論文、報告書等がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE,],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS],
        ];
    }

    /**
     * test_download_実績を示す論文、報告書等がアップロードされていない場合チェックなし
     *
     * 添付文書(実績を示す論文、報告書等)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_実績を示す論文、報告書等がアップロードされていない場合チェックなし
     */
    public function test_download_実績を示す論文、報告書等がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：実績を示す論文・報告書等/', $contentsEachPage[2], $result);

        // Assertion
        // 2ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_実績を示す論文、報告書等がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_実績を示す論文、報告書等がアップロードされていない場合チェックなし()
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
