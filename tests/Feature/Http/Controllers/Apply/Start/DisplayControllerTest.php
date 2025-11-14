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

namespace Tests\Feature\Http\Controllers\Apply\Start;

use App\Models\Apply;
use App\Models\User;
use Tests\Feature\FeatureTestBase;

class DisplayControllerTest extends FeatureTestBase
{
    const ACTOR_IS_OWNER = 10;
    const ACTOR_IS_OTHER_APPLICANT = 90;
    const ACTOR_IS_SECRETARIAT = 91;

    /**
     * testA
     * 申請開始画面の表示を行う
     * @param $roleId
     * @param $HttpStatusCode
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider displayStartScreenDataProvider
     */
    public function testDisplayStartScreenForCreate($roleId, $HttpStatusCode)
    {
        $actor = User::factory()->state(['role_id' => $roleId])->create();
        $response = $this->actingAs($actor)->get('/apply/start');
        $response->assertStatus($HttpStatusCode);
    }

    public function displayStartScreenDataProvider()
    {
        return [
            [2, 403], //事務局、申請開始画面の利用不可
            [3, 200], //申請者、申請開始画面の利用OK
        ];
    }

    /**
     * testDisplayForUpdate
     *
     * @param int $actorIs
     * @param int $applyStatusId
     * @param int $expectedResponseCode
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider forUpdateDataProvider
     */
    public function testDisplayForUpdate(int $actorIs, int $applyStatusId, int $expectedResponseCode)
    {
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
        ])->create();

        //Execution
        $response = $this->actingAs($actor)->get('/apply/start/' . $targetApply->id);

        //Assertions
        $response->assertStatus($expectedResponseCode);
    }

    public function forUpdateDataProvider()
    {
        return [
            //ActorType,ApplyStatus,ExpectedHttpStatus
            [self::ACTOR_IS_OWNER, 1, 200], //申請者本人がステータス1の申請を表示する場合のみ、許可される
            [self::ACTOR_IS_OWNER, 2, 403],
            [self::ACTOR_IS_OWNER, 3, 403],
            [self::ACTOR_IS_OWNER, 4, 403],
            [self::ACTOR_IS_OWNER, 5, 403],
            [self::ACTOR_IS_OWNER, 99, 403],

            [self::ACTOR_IS_SECRETARIAT, 1, 403],
            [self::ACTOR_IS_SECRETARIAT, 2, 403],
            [self::ACTOR_IS_SECRETARIAT, 3, 403],
            [self::ACTOR_IS_SECRETARIAT, 4, 403],
            [self::ACTOR_IS_SECRETARIAT, 5, 403],
            [self::ACTOR_IS_SECRETARIAT, 99, 403],

            [self::ACTOR_IS_OTHER_APPLICANT, 1, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 2, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 3, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 4, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 5, 403],
            [self::ACTOR_IS_OTHER_APPLICANT, 99, 403],
        ];
    }
}
