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

namespace Tests\Feature\Http\Controllers\Pdf;

use App\Models\Apply;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Tests\Feature\FeatureTestBase;

/**
 * ApplicantCanDownloadPdfTest
 *
 * @package Http\Controllers\Pdf
 */
class ApplyPdfDownloadControllerTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * test_download_status
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int|null $applyType
     * @param int $expectedResponseCode
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider nDataProvider_status
     */
    public function test_download_status(
        int $actorIs,
        int $applyStatusId,
        ?int $applyType,
        int $expectedResponseCode
    ) {
        //Preparations
        //Owner
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        //Actor
        $actor = match ($actorIs) {
            self::ACTOR_IS_OWNER => $applicantOwner,
            self::ACTOR_IS_OTHER_APPLICANT => User::factory()->state(['role_id' => 3])->create(),
            self::ACTOR_IS_SECRETARIAT => User::factory()->state(['role_id' => 2])->create()
        };

        //Apply
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
            'type_id' => $applyType,
        ])->create();

        //Execution
        $response = $this->actingAs($actor)->get('/pdf/apply/download/' . $targetApply->id);

        //Assertion
        $response->assertStatus($expectedResponseCode);
    }

    public function nDataProvider_status()
    {
        return [
            // 申請者本人
            [self::ACTOR_IS_OWNER, 1, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_OWNER, 2, ApplyTypes::GOVERNMENT_LINKAGE, 200],//申出文書 作成中
            [self::ACTOR_IS_OWNER, 3, ApplyTypes::GOVERNMENT_LINKAGE, 200],//申出文書 確認中
            [self::ACTOR_IS_OWNER, 4, ApplyTypes::GOVERNMENT_LINKAGE, 200],//申出文書 提出中
            [self::ACTOR_IS_OWNER, 5, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_OWNER, ApplyStatuses::ACCEPTED, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_OWNER, 99, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_OWNER, 1, null, 404],
            [self::ACTOR_IS_OWNER, 2, null, 404],
            [self::ACTOR_IS_OWNER, 3, null, 404],
            [self::ACTOR_IS_OWNER, 4, null, 404],
            [self::ACTOR_IS_OWNER, 5, null, 404],
            [self::ACTOR_IS_OWNER, ApplyStatuses::ACCEPTED, null, 404],
            [self::ACTOR_IS_OWNER, 99, null, 404],

            //申請者以外、状態に関係なく403
            [self::ACTOR_IS_OTHER_APPLICANT, 1, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 2, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 4, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 5, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, ApplyStatuses::ACCEPTED, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 99, ApplyTypes::GOVERNMENT_LINKAGE, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 1, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 2, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 3, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 4, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 5, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, ApplyStatuses::ACCEPTED, null, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 99, null, 403],


            //事務局
            [self::ACTOR_IS_SECRETARIAT, 1, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_SECRETARIAT, 2, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_SECRETARIAT, 3, ApplyTypes::GOVERNMENT_LINKAGE, 200],//申出文書 確認中
            [self::ACTOR_IS_SECRETARIAT, 4, ApplyTypes::GOVERNMENT_LINKAGE, 200],//申出文書 提出中
            [self::ACTOR_IS_SECRETARIAT, 5, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_SECRETARIAT, ApplyStatuses::ACCEPTED, ApplyTypes::GOVERNMENT_LINKAGE, 200],//応諾
            [self::ACTOR_IS_SECRETARIAT, 99, ApplyTypes::GOVERNMENT_LINKAGE, 404],
            [self::ACTOR_IS_SECRETARIAT, 1, null, 404],
            [self::ACTOR_IS_SECRETARIAT, 2, null, 404],
            [self::ACTOR_IS_SECRETARIAT, 3, null, 404],
            [self::ACTOR_IS_SECRETARIAT, 4, null, 404],
            [self::ACTOR_IS_SECRETARIAT, 5, null, 404],
            [self::ACTOR_IS_SECRETARIAT, ApplyStatuses::ACCEPTED, null, 404],
            [self::ACTOR_IS_SECRETARIAT, 99, null, 404],

        ];
    }
}
