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
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Tests\Feature\FeatureTestBase;

/**
 * CommonSpecificationTest
 *
 * PDF出力内容テスト(PDF全体に関する仕様)
 *
 * @package Http\Controllers\Pdf
 */
class CommonSpecificationTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_出力を行う
     *
     * 申出者本人、もしくは事務局権限のユーザーすべて
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_出力を行う
     */
    public function test_download_出力を行う(
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

        // Assertion
        // 出力されていること
        $this->assertMatchesRegularExpression('/<div class="pdf-page">/', $response->content());
    }

    /**
     * nDataProvider_出力を行う
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_出力を行う()
    {
        return [
            // 申請者本人
            [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 作成中
            [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 確認中
            [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 提出中

            // 事務局
            [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 確認中
            [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 提出中
            [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::GOVERNMENT_LINKAGE],// 応諾
        ];
    }

    /**
     * test_download_出力を行わない_禁止
     *
     * 申出者本人以外
     * 「禁止されています」文言が表示される
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_出力を行わない_禁止
     */
    public function test_download_出力を行わない_禁止(
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

        // Assertion
        // 出力されていないこと
        $this->assertDoesNotMatchRegularExpression('/<div class="pdf-page">/', $response->content());
        $this->assertMatchesRegularExpression('/禁止されています/', $response->content());
    }

    /**
     * nDataProvider_出力を行わない_禁止
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_出力を行わない_禁止()
    {
        return [
            // 申請者以外
            [self::ACTOR_IS_OTHER_APPLICANT, 1, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 2, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 3, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 4, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, ApplyStatuses::UNDER_REVIEW, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 20, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 99, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OTHER_APPLICANT, 1, null],
            [self::ACTOR_IS_OTHER_APPLICANT, 2, null],
            [self::ACTOR_IS_OTHER_APPLICANT, 3, null],
            [self::ACTOR_IS_OTHER_APPLICANT, 4, null],
            [self::ACTOR_IS_OTHER_APPLICANT, ApplyStatuses::UNDER_REVIEW, null],
            [self::ACTOR_IS_OTHER_APPLICANT, 20, null],
            [self::ACTOR_IS_OTHER_APPLICANT, 99, null],
        ];
    }

    /**
     * test_download_出力を行わない_対象外データ
     *
     * 申出ステータスや申出者が対象外
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_出力を行わない_対象外データ
     */
    public function test_download_出力を行わない_対象外データ(
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

        // Assertion
        // 出力されていないこと
        $this->assertDoesNotMatchRegularExpression('/<div class="pdf-page">/', $response->content());
        $this->assertDoesNotMatchRegularExpression('/禁止されています/', $response->content());
    }

    /**
     * nDataProvider_出力を行わない_対象外データ
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_出力を行わない_対象外データ()
    {
        return [
            // 申請者本人
            [self::ACTOR_IS_OWNER, 1, ApplyTypes::GOVERNMENT_LINKAGE],// データ提供可否 相談中
            [self::ACTOR_IS_OWNER, ApplyStatuses::UNDER_REVIEW, ApplyTypes::GOVERNMENT_LINKAGE],// 審査中
            [self::ACTOR_IS_OWNER, 20, ApplyTypes::GOVERNMENT_LINKAGE],// 応諾
            [self::ACTOR_IS_OWNER, 99, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_OWNER, 1, null],
            [self::ACTOR_IS_OWNER, 2, null],
            [self::ACTOR_IS_OWNER, 3, null],
            [self::ACTOR_IS_OWNER, 4, null],
            [self::ACTOR_IS_OWNER, ApplyStatuses::UNDER_REVIEW, null],
            [self::ACTOR_IS_OWNER, 20, null],
            [self::ACTOR_IS_OWNER, 99, null],

            // 事務局
            [self::ACTOR_IS_SECRETARIAT, 1, ApplyTypes::GOVERNMENT_LINKAGE],// データ提供可否 相談中
            [self::ACTOR_IS_SECRETARIAT, 2, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 作成中
            [self::ACTOR_IS_SECRETARIAT, ApplyStatuses::UNDER_REVIEW, ApplyTypes::GOVERNMENT_LINKAGE],// 審査中
            [self::ACTOR_IS_SECRETARIAT, 99, ApplyTypes::GOVERNMENT_LINKAGE],
            [self::ACTOR_IS_SECRETARIAT, 1, null,],
            [self::ACTOR_IS_SECRETARIAT, 2, null],
            [self::ACTOR_IS_SECRETARIAT, 3, null],
            [self::ACTOR_IS_SECRETARIAT, 4, null],
            [self::ACTOR_IS_SECRETARIAT, ApplyStatuses::UNDER_REVIEW, null],
            [self::ACTOR_IS_SECRETARIAT, 20, null],
            [self::ACTOR_IS_SECRETARIAT, 99, null],
        ];
    }

    /**
     * test_download_透かし文字あり
     *
     * 以下条件に当てはまる場合、透かし文字を表示するPDFを作成する
     * ・PDF出力できる状態
     * ・申出者
     * ・申出ステータスが「提出中」以外
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_透かし文字あり
     */
    public function test_download_透かし文字あり(
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

        // Assertion
        // 「提出不可」透かし文字を表示するクラスがあること
        $this->assertMatchesRegularExpression('/<div class="main  sample ">/', $response->content());
    }

    /**
     * nDataProvider_透かし文字あり
     *
     * 申出ステータスにより出力されないパターンはテスト済みなので除外
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_透かし文字あり()
    {
        return [
            // 申請者本人
            [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 作成中
            [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 確認中
        ];
    }

    /**
     * test_download_透かし文字なし
     *
     * 以下条件に当てはまる場合、透かし文字を表示しないPDFを作成する
     * ・PDF出力できる状態
     * ・申出ステータスが「確認中」、「提出中」(事務局)
     * ・申出ステータスが「提出中」(申出者)
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_透かし文字なし
     */
    public function test_download_透かし文字なし(
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

        // Assertion
        // 「提出不可」透かし文字を表示しないクラスがあること
        $this->assertMatchesRegularExpression('/<div class="main ">/', $response->content());
        $this->assertDoesNotMatchRegularExpression('/<div class="main  sample ">/', $response->content());
    }

    /**
     * nDataProvider_透かし文字なし
     *
     * 申出ステータスにより出力されないパターンはテスト済みなので除外
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_透かし文字なし()
    {
        return [
            // 申請者本人
            [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 提出中

            // 事務局
            [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 確認中
            [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE],// 申出文書 提出中
        ];
    }
}
