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
 *  UsageEnvironmentTest
 *
 * PDF出力内容テスト(7.利用場所、利用する環境、保管場所及び管理方法)
 *
 * @package Http\Controllers\Pdf
 */
class UsageEnvironmentTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_安全管理措置がアップロードされていたらチェック
     *
     * 添付文書(安全管理措置)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param array $attachmentTypeList
     * @param string $exceptCheck
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_安全管理措置がアップロードされていたらチェック
     */
    public function test_download_安全管理措置がアップロードされていたらチェック(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        array $attachmentTypeList,
        string $exceptCheck
    ) {
        // Preparations
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
        ])->create();

        $this->buildAttachment($applicantOwner->id, $targetApply->id, $attachmentTypeList);

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：安全管理措置/', $contentsEachPage[6], $result);

        // Assertion
        // 6ページ目の対象添付文書がアップロードされていたらチェック
        $this->assertMatchesRegularExpression(sprintf('/%s/', $exceptCheck), $result[1]);
    }

    /**
     * nDataProvider_安全管理措置がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_安全管理措置がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "行政関係者・リンケージ利用_対象ファイルアップロード済" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, [701], '☑'],
            "行政関係者・集計統計利用_対象と複数ファイルアップロード済" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, [101, 201, 701], '☑'],
            "研究者等・リンケージ利用_アップロードなし" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, [], '☐'],
            "研究者等・集計統計利用_対象ファイル以外アップロード済" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, [102, 202], '☐'],

            // 事務局
            "行政関係者・リンケージ利用_対象ファイルアップロード済" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, [701], '☑'],
            "行政関係者・集計統計利用_対象と複数ファイルアップロード済" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, [101, 201, 701], '☑'],
            "研究者等・リンケージ利用_アップロードなし" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, [], '☐'],
            "研究者等・集計統計利用_対象ファイル以外アップロード済" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, [102, 202], '☐'],
        ];
    }

    /**
     * buildAttachment
     *
     * 添付ファイルデータ作成
     *
     * @param int $userId
     * @param int $applyId
     * @param array $parameter
     * @return void
     * @author m.shomura <m.shomura@balocco.info>
     */
    private function buildAttachment(int $userId, int $applyId, array $parameter): void
    {
        foreach ($parameter as $typeId) {
            Attachment::factory()->state([
                'user_id' => $userId,
                'apply_id' => $applyId,
                'attachment_type_id' => $typeId,
                'status' => AttachmentStatuses::SUBMITTING,
            ])->create();
        }
    }
}
