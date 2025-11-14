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
use App\Models\ApplyUser;
use App\Models\User;
use App\Models\Attachment;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Tests\Feature\FeatureTestBase;

/**
 *  MethodologyOfPublicTableTest
 *
 * PDF出力内容テスト(8.調査研究成果の公表方法及び公表予定時期)
 *
 * @package Http\Controllers\Pdf
 */
class MethodologyOfPublicTableTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_調査研究成果の公表方法及び公表予定時期を出力
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string|null $expectText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_調査研究成果の公表方法及び公表予定時期を出力
     */
    public function test_download_調査研究成果の公表方法及び公表予定時期を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $expectText
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
            '8_scheduled_to_be_announced' => $expectText
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        $targetTextList = $this->fetchTargetTextList($response->content());

        // Assertion
        // 6ページ目の公表方法及び公表予定時期が一致すること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $expectText), $targetTextList[3]);
    }

    /**
     * nDataProvider_調査研究成果の公表方法及び公表予定時期を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_調査研究成果の公表方法及び公表予定時期を出力()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, '公表方法テスト改行なし1'],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, '公表方法テスト改行なし2'],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, '公表方法テスト改行なし3'],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, '公表方法テスト改行なし4'],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, '公表方法テスト改行なし5'],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, '公表方法テスト改行なし6'],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, '公表方法テスト改行なし7'],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, null],
        ];
    }

    /**
     * test_download_調査研究成果の公表方法及び公表予定時期を出力_改行あり
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $scheduledToBeAnnounced
     * @param string $expectText
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_調査研究成果の公表方法及び公表予定時期を出力_改行あり
     */
    public function test_download_調査研究成果の公表方法及び公表予定時期を出力_改行あり(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $scheduledToBeAnnounced,
        string $expectText
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
            '8_scheduled_to_be_announced' => $scheduledToBeAnnounced
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        $targetTextList = $this->fetchTargetTextList($response->content());

        // Assertion
        // 6ページ目の公表方法及び公表予定時期に改行コードがあること
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $expectText), $targetTextList[3]);
    }

    /**
     * nDataProvider_調査研究成果の公表方法及び公表予定時期を出力_改行あり
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_調査研究成果の公表方法及び公表予定時期を出力_改行あり()
    {
        $text = <<<EOT
        公表方法テスト%s
        改行テスト
        EOT;
        $exceptText = "公表方法テスト%s";
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
     * fetchTargetTextList
     *
     * PDFテキストをページ毎に分割し、対象項目前後で分割
     *
     * @param string $text
     * @return array
     */
    private function fetchTargetTextList(string $text)
    {
        return preg_split('/(<p class="pdl1">|<p class="mgt1">")/', explode('<div class="pdf-page">', $text)[6]);
    }
}
