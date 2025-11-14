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
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Tests\Feature\FeatureTestBase;

/**
 *  ApplicantAndApplyUserTest
 *
 * PDF出力内容テスト(3.提供依頼申出者及び利用者)
 *
 * @package Http\Controllers\Pdf
 */
class ApplicantAndApplyUserTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_提供依頼申出者が個人または対象外データの場合は氏名、住所、生年月日を出力
     *
     * 提供依頼申出者が個人または対象外データの場合は以下を出力
     * ・氏名
     * ・住所
     * ・生年月日
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param ?string $applicantName
     * @param ?string $applicantAddress
     * @param ?string $applicantBirthday
     * @param int $applicantType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_提供依頼申出者が個人または対象外データの場合は氏名、住所、生年月日を出力
     */
    public function test_download_提供依頼申出者が個人または対象外データの場合は氏名、住所、生年月日を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $applicantName,
        ?string $applicantAddress,
        ?string $applicantBirthday,
        int $applicantType
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
            '10_applicant_type' => $applicantType,
            '10_applicant_name' => $applicantName,
            '10_applicant_address' => $applicantAddress,
            '10_applicant_birthday' => $applicantBirthday
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/(<p class="pdl2">| <p class="mgt1 pdl1>")/', $contentsEachPage[3]);

        // Assertion
        // 3ページ目の各項目が一致していること
        $this->assertMatchesRegularExpression(sprintf('/氏名：%s/', $applicantName), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/住所：%s/', $applicantAddress), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/生年月日：%s/', $applicantBirthday), $targetTextList[1]);
    }

    /**
     * nDataProvider_提供依頼申出者が個人または対象外データの場合は氏名、住所、生年月日を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_提供依頼申出者が個人または対象外データの場合は氏名、住所、生年月日を出力()
    {
        return [
            // 申請者本人
            "申請者本人_個人_すべて空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null, 1],
            "申請者本人_個人_氏名のみ" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 'テスト氏名2', null, null, 1],
            "申請者本人_個人_住所のみ" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, null, 'テスト住所3', null, 1],
            "申請者本人_個人_生年月日のみ" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, '2023-07-04', 1],
            "申請者本人_個人_すべて出力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_STATISTICS, 'テスト氏名5', 'テスト住所5', '2023-07-05', 1],
            "申請者本人_対象外データ_すべて出力" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_STATISTICS, 'テスト氏名6', 'テスト住所6', '2023-07-06', 3],

            // 事務局
            "事務局_個人_すべて空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null, 1],
            "事務局_個人_氏名のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, null, 'テスト住所8', '2023-07-08', 1],
            "事務局_個人_住所のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 'テスト氏名9', null, '2023-07-09', 1],
            "事務局_個人_生年月日のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 'テスト氏名10', 'テスト住所10', null, 1],
            "事務局_個人_すべて出力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, 'テスト氏名11', 'テスト住所11', '2023-07-11', 1],
            "事務局_対象外データ_すべて出力" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_STATISTICS, 'テスト氏名12', 'テスト住所12', '2023-07-12', 3],
        ];
    }

    /**
     * test_download_提供依頼申出者が法人の場合は代表者氏名、法人その他の団体の名称、法人その他の団体の住所を出力
     *
     * 提供依頼申出者が法人の場合は以下を出力
     * ・代表者氏名
     * ・法人その他の団体の名称
     * ・法人その他の団体の住所
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param ?string $applicantName
     * @param ?string $applicantAddress
     * @param ?string $affiliation
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_提供依頼申出者が法人の場合は代表者氏名、法人その他の団体の名称、法人その他の団体の住所を出力
     */
    public function test_download_提供依頼申出者が法人の場合は代表者氏名、法人その他の団体の名称、法人その他の団体の住所を出力(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        ?string $applicantName,
        ?string $applicantAddress,
        ?string $affiliation
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
            '10_applicant_type' => 2,// 法人
            '10_applicant_name' => $applicantName,
            '10_applicant_address' => $applicantAddress,
            'affiliation' => $affiliation
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/(<p class="pdl2">| <p class="mgt1 pdl1>")/', $contentsEachPage[3]);

        // Assertion
        // 3ページ目の各項目が一致していること
        $this->assertMatchesRegularExpression(sprintf('/代表者氏名：%s/', $applicantName), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/法人その他の団体の住所：%s/', $applicantAddress), $targetTextList[1]);
        $this->assertMatchesRegularExpression(sprintf('/法人その他の団体の名称：%s/', $affiliation), $targetTextList[1]);
    }

    /**
     * nDataProvider_提供依頼申出者が法人の場合は代表者氏名、法人その他の団体の名称、法人その他の団体の住所を出力
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_提供依頼申出者が法人の場合は代表者氏名、法人その他の団体の名称、法人その他の団体の住所を出力()
    {
        return [
            // 申請者本人
            "申請者本人_すべて空欄" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null],
            "申請者本人_氏名のみ" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, 'テスト代表者氏名2', null, null],
            "申請者本人_住所のみ" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, null, 'テスト団体住所3', null],
            "申請者本人_団体の名称のみ" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, null, null, 'テスト団体名称4'],
            "申請者本人_すべて出力" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::CIVILIAN_STATISTICS, 'テスト代表者氏名5', 'テスト団体住所5', 'テスト団体名称5'],

            // 事務局
            "事務局_すべて空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, null, null, null],
            "事務局_氏名のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, null, 'テスト団体住所7', 'テスト団体名称7'],
            "事務局_住所のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, 'テスト代表者氏名8', null, 'テスト団体名称8'],
            "事務局_団体の名称のみ空欄" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 'テスト代表者氏名9', 'テスト団体住所9', null],
            "事務局_すべて出力" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::CIVILIAN_STATISTICS, 'テスト代表者氏名10', 'テスト団体住所10', 'テスト団体名称10'],
        ];
    }

    /**
     * test_download_様式例第2ー3号及び誓約書がアップロードされていたらチェック
     *
     * 添付文書(様式例第2ー3号及び誓約書)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第2ー3号及び誓約書がアップロードされていたらチェック
     */
    public function test_download_様式例第2ー3号及び誓約書がアップロードされていたらチェック(
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
            'attachment_type_id' => 301,// 同意書等
            'status' => AttachmentStatuses::SUBMITTING
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：様式例第2-3号及び誓約書/', $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[1]);
    }

    /**
     * nDataProvider_様式例第2ー3号及び誓約書がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第2ー3号及び誓約書がアップロードされていたらチェック()
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
     * test_download_様式例第2ー3号及び誓約書がアップロードされていない場合チェックなし
     *
     * 添付文書(様式例第2ー3号及び誓約書)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第2ー3号及び誓約書がアップロードされていない場合チェックなし
     */
    public function test_download_様式例第2ー3号及び誓約書がアップロードされていない場合チェックなし(
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
        preg_match('/(.*)添付：様式例第2-3号及び誓約書/', $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていないこと
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_様式例第2ー3号及び誓約書がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第2ー3号及び誓約書がアップロードされていない場合チェックなし()
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
     * test_download_様式例第2ー3号及び誓約書が「アップロード」ステータスの場合チェックなし
     *
     * 添付文書(様式例第2ー3号及び誓約書)が「アップロード」ステータスの場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $applyType
     * @param int $attachmentStatusId
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第2ー3号及び誓約書が「アップロード」ステータスの場合チェックなし
     */
    public function test_download_様式例第2ー3号及び誓約書が「アップロード」ステータスの場合チェックなし(
        int $actorIs,
        int $applyStatusId,
        int $applyType,
        int $attachmentStatusId
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
            'attachment_type_id' => 301,// 同意書等
            'status' => $attachmentStatusId
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match('/(.*)添付：様式例第2-3号及び誓約書/', $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていないこと
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[1]);
    }

    /**
     * nDataProvider_様式例第2ー3号及び誓約書が「アップロード」ステータスの場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第2ー3号及び誓約書が「アップロード」ステータスの場合チェックなし()
    {
        return [
            "申請者本人" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, AttachmentStatuses::UPLOADED],
            "事務局" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, AttachmentStatuses::UPLOADED],
        ];
    }

    /**
     * test_download_委託契約書がアップロードされていたらチェック
     *
     * 添付文書(委託契約書)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $AttachmentName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_委託契約書がアップロードされていたらチェック
     */
    public function test_download_委託契約書がアップロードされていたらチェック(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $AttachmentName
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
            'attachment_type_id' => 303,// 委託契約書
            'status' => AttachmentStatuses::SUBMITTING
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match(sprintf('/(.*)%s/', $AttachmentName), $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[0]);
    }

    /**
     * nDataProvider_委託契約書がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_委託契約書がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
        ];
    }

    /**
     * test_download_委託契約書がアップロードされていない場合チェックなし
     *
     * 添付文書(委託契約書)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $AttachmentName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_委託契約書がアップロードされていない場合チェックなし
     */
    public function test_download_委託契約書がアップロードされていない場合チェックなし(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $AttachmentName
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
        preg_match(sprintf('/(.*)%s/', $AttachmentName), $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[0]);
    }

    /**
     * nDataProvider_委託契約書がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_委託契約書がアップロードされていない場合チェックなし()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
        ];
    }

    /**
     * test_download_様式例第4ー2号がアップロードされていたらチェック
     *
     * 添付文書(様式例第4ー2号)がアップロードされている場合、チェック
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $AttachmentName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第4ー2号がアップロードされていたらチェック
     */
    public function test_download_様式例第4ー2号がアップロードされていたらチェック(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $AttachmentName
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
            'attachment_type_id' => 302,// 委託契約書
            'status' => AttachmentStatuses::SUBMITTING
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        preg_match(sprintf('/(.*)%s/', $AttachmentName), $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☑'), $result[0]);
    }

    /**
     * nDataProvider_様式例第4ー2号がアップロードされていたらチェック
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第4ー2号がアップロードされていたらチェック()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
        ];
    }

    /**
     * test_download_様式例第4ー2号がアップロードされていない場合チェックなし
     *
     * 添付文書(様式例第4ー2号)がアップロードされていない場合、チェックしない
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $AttachmentName
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_様式例第4ー2号がアップロードされていない場合チェックなし
     */
    public function test_download_様式例第4ー2号がアップロードされていない場合チェックなし(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $AttachmentName
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
        preg_match(sprintf('/(.*)%s/', $AttachmentName), $contentsEachPage[3], $result);

        // Assertion
        // 3ページ目の対象添付文書がチェックされていること
        $this->assertMatchesRegularExpression(sprintf('/%s/', '☐'), $result[0]);
    }

    /**
     * nDataProvider_様式例第4ー2号がアップロードされていない場合チェックなし
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_様式例第4ー2号がアップロードされていない場合チェックなし()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "調査研究を委託している場合は、委託契約書等又は様式例第4-1号又は様式例第4-2号"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "添付：調査研究の一部を委託している場合は、委託契約書等又は様式例第4-2号"],
        ];
    }

    /**
     * test_download_利用者情報に氏名、所属、職名、役割を出力_1名
     *
     * 利用者情報に以下を出力
     * ・氏名
     * ・所属
     * ・職名
     * ・役割
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $userName
     * @param string $userInstitution
     * @param string $userPosition
     * @param string $userRole
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名
     */
    public function test_download_利用者情報に氏名、所属、職名、役割を出力_1名(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $userName,
        string $userInstitution,
        string $userPosition,
        string $userRole
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

        // ApplyUser
        ApplyUser::factory()->state([
            'apply_id' => $targetApply->id,
            'name' => $userName,
            'institution' => $userInstitution,
            'position' => $userPosition,
            'role' => $userRole,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/(<dl class="applyUser">|<dt>)/', $contentsEachPage[3]);

        // Assertion
        // 3ページ目の各項目が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userName), $targetTextList[2]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userInstitution), $targetTextList[3]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userPosition), $targetTextList[4]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userRole), $targetTextList[5]);
    }

    /**
     * nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名1", "テスト所属1", "テスト職名1", "テスト役割1"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名2", "テスト所属2", "テスト職名2", "テスト役割2"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名3", "テスト所属3", "テスト職名3", "テスト役割3"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名4", "テスト所属4", "テスト職名4", "テスト役割4"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名5", "テスト所属5", "テスト職名5", "テスト役割5"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名6", "テスト所属6", "テスト職名6", "テスト役割6"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名7", "テスト所属7", "テスト職名7", "テスト役割7"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名8", "テスト所属8", "テスト職名8", "テスト役割8"],
        ];
    }

    /**
     * test_download_利用者情報に氏名、所属、職名、役割を出力_1名_改行あり
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $userName
     * @param string $userInstitution
     * @param string $userPosition
     * @param string $userRole
     * @param string $expectUserInstitution
     * @param string $expectUserPosition
     * @param string $expectUserRole
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名_改行あり
     */
    public function test_download_利用者情報に氏名、所属、職名、役割を出力_1名_改行あり(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $userName,
        string $userInstitution,
        string $userPosition,
        string $userRole,
        string $expectUserInstitution,
        string $expectUserPosition,
        string $expectUserRole
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

        // ApplyUser
        ApplyUser::factory()->state([
            'apply_id' => $targetApply->id,
            'name' => $userName,
            'institution' => $userInstitution,
            'position' => $userPosition,
            'role' => $userRole,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/(<dl class="applyUser">|<dt>)/', $contentsEachPage[3]);

        // Assertion
        // 3ページ目の各項目が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userName), $targetTextList[2]);
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $expectUserInstitution), $targetTextList[3]);
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $expectUserPosition), $targetTextList[4]);
        $this->assertMatchesRegularExpression(sprintf('/%s<br/', $expectUserRole), $targetTextList[5]);
    }

    /**
     * nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名_改行あり
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_利用者情報に氏名、所属、職名、役割を出力_1名_改行あり()
    {
        $userInstitution = <<<EOT
        テスト所属%s
        改行テスト
        EOT;
        $userPosition = <<<EOT
        テスト職名%s
        改行テスト
        EOT;
        $userRole = <<<EOT
        テスト役割%s
        改行テスト
        EOT;

        $expectUserInstitution = "テスト所属%s";
        $expectUserPosition = "テスト職名%s";
        $expectUserRole = "テスト役割%s";

        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名1", sprintf($userInstitution, 1), sprintf($userPosition, 1), sprintf($userRole, 1), sprintf($expectUserInstitution, 1), sprintf($expectUserPosition, 1), sprintf($expectUserRole, 1)],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名2", sprintf($userInstitution, 2), sprintf($userPosition, 2), sprintf($userRole, 2), sprintf($expectUserInstitution, 2), sprintf($expectUserPosition, 2), sprintf($expectUserRole, 2)],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名3", sprintf($userInstitution, 3), sprintf($userPosition, 3), sprintf($userRole, 3), sprintf($expectUserInstitution, 3), sprintf($expectUserPosition, 3), sprintf($expectUserRole, 3)],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名4", sprintf($userInstitution, 4), sprintf($userPosition, 4), sprintf($userRole, 4), sprintf($expectUserInstitution, 4), sprintf($expectUserPosition, 4), sprintf($expectUserRole, 4)],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名5", sprintf($userInstitution, 5), sprintf($userPosition, 5), sprintf($userRole, 5), sprintf($expectUserInstitution, 5), sprintf($expectUserPosition, 5), sprintf($expectUserRole, 5)],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名6", sprintf($userInstitution, 6), sprintf($userPosition, 6), sprintf($userRole, 6), sprintf($expectUserInstitution, 6), sprintf($expectUserPosition, 6), sprintf($expectUserRole, 6)],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名7", sprintf($userInstitution, 7), sprintf($userPosition, 7), sprintf($userRole, 7), sprintf($expectUserInstitution, 7), sprintf($expectUserPosition, 7), sprintf($expectUserRole, 7)],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名8", sprintf($userInstitution, 8), sprintf($userPosition, 8), sprintf($userRole, 8), sprintf($expectUserInstitution, 8), sprintf($expectUserPosition, 8), sprintf($expectUserRole, 8)],
        ];
    }

    /**
     * test_download_利用者情報に氏名、所属、職名、役割を出力_2名
     *
     * 利用者情報に以下を出力
     * ・氏名
     * ・所属
     * ・職名
     * ・役割
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param string $userName
     * @param string $userInstitution
     * @param string $userPosition
     * @param string $userRole
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider nDataProvider_利用者情報に氏名、所属、職名、役割を出力_2名
     */
    public function test_download_利用者情報に氏名、所属、職名、役割を出力_2名(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        string $userName,
        string $userInstitution,
        string $userPosition,
        string $userRole,
        string $userName2,
        string $userInstitution2,
        string $userPosition2,
        string $userRole2
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

        // ApplyUser
        ApplyUser::factory()->state([
            'apply_id' => $targetApply->id,
            'name' => $userName,
            'institution' => $userInstitution,
            'position' => $userPosition,
            'role' => $userRole,
        ])->create();

        // ApplyUser(二人目)
        ApplyUser::factory()->state([
            'apply_id' => $targetApply->id,
            'name' => $userName2,
            'institution' => $userInstitution2,
            'position' => $userPosition2,
            'role' => $userRole2,
        ])->create();

        // Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);
        // ページ毎に分割
        $contentsEachPage = explode('<div class="pdf-page">', $response->content());
        // 対象項目前後の文字で分割
        $targetTextList = preg_split('/(<dl class="applyUser">|<dt>)/', $contentsEachPage[3]);

        // Assertion
        // 3ページ目の各項目が一致していること
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userName), $targetTextList[2]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userInstitution), $targetTextList[3]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userPosition), $targetTextList[4]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userRole), $targetTextList[5]);
        // 二人目
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userName2), $targetTextList[7]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userInstitution2), $targetTextList[8]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userPosition2), $targetTextList[9]);
        $this->assertMatchesRegularExpression(sprintf('/%s/', $userRole2), $targetTextList[10]);
    }

    /**
     * nDataProvider_利用者情報に氏名、所属、職名、役割を出力_2名
     *
     * 改行表示の確認は1名テストケースで行っているため省略
     *
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function nDataProvider_利用者情報に氏名、所属、職名、役割を出力_2名()
    {
        return [
            // 申請者本人
            "申請者本人_行政関係者・リンケージ利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名1-1", "テスト所属1-1", "テスト職名1-1", "テスト役割1-1", "テスト氏名1-2", "テスト所属1-2", "テスト職名1-2", "テスト役割1-2"],
            "申請者本人_行政関係者・集計統計利用" => [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名2-1", "テスト所属2-1", "テスト職名2-1", "テスト役割2-1", "テスト氏名2-2", "テスト所属2-2", "テスト職名2-2", "テスト役割2-2"],
            "申請者本人_研究者等・リンケージ利用" => [self::ACTOR_IS_OWNER, 4, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名3-1", "テスト所属3-1", "テスト職名3-1", "テスト役割3-1", "テスト氏名3-2", "テスト所属3-2", "テスト職名3-2", "テスト役割3-2"],
            "申請者本人_研究者等・集計統計利用" => [self::ACTOR_IS_OWNER, 2, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名4-1", "テスト所属4-1", "テスト職名4-1", "テスト役割4-1", "テスト氏名4-2", "テスト所属4-2", "テスト職名4-2", "テスト役割4-2"],

            // 事務局
            "事務局_行政関係者・リンケージ利用用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, "テスト氏名5-1", "テスト所属5-1", "テスト職名5-1", "テスト役割5-1", "テスト氏名5-2", "テスト所属5-2", "テスト職名5-2", "テスト役割5-2"],
            "事務局_行政関係者・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_STATISTICS, "テスト氏名6-1", "テスト所属6-1", "テスト職名6-1", "テスト役割6-1", "テスト氏名6-2", "テスト所属6-2", "テスト職名6-2", "テスト役割6-2"],
            "事務局_研究者等・リンケージ利用" => [self::ACTOR_IS_SECRETARIAT, 20, ApplyTypes::CIVILIAN_LINKAGE, "テスト氏名7-1", "テスト所属7-1", "テスト職名7-1", "テスト役割7-1", "テスト氏名7-2", "テスト所属7-2", "テスト職名7-2", "テスト役割7-2"],
            "事務局_研究者等・集計統計利用" => [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::CIVILIAN_STATISTICS, "テスト氏名8-1", "テスト所属8-1", "テスト職名8-1", "テスト役割8-1", "テスト氏名8-2", "テスト所属8-2", "テスト職名8-2", "テスト役割8-2"],
        ];
    }
}
