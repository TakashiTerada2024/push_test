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

namespace Tests\Feature\Http\Controllers\Attachment\Apply;

use App\Models\Apply;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Tests\Feature\FeatureTestBase;

/**
 * Class ChooseTypeControllerTest
 * @package Tests\Feature\Http\Controllers\Attachment\Apply
 */
class ChooseTypeControllerTest extends FeatureTestBase
{
    /**
     * test_OwnerCanOrCannotChoseType
     * 申出者による添付ファイル種別の変更権限をテストする
     * @covers       \App\Http\Controllers\Attachment\Apply\ChooseTypeController
     * @param int $applyStatusId
     * @param int $statusExpected
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider ownerCanChoseTypeStatusDataProvider
     */
    public function test_OwnerCanOrCannotChoseType(int $applyStatusId, int $statusExpected)
    {
        //テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();
        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatusId,
        ])->create();

        //申出者本人によるアクセスをテスト（パラメータを与えていないため、保存処理本体は実行されない。）
        $response = $this->actingAs($applicantOwner)->post('/attachment/apply/choose_type/' . $targetApply->id, []);
        $response->assertStatus($statusExpected);
        if ($statusExpected === 302) {
            //成功するパターンでは、添付ファイル表示のルートにリダイレクトされることを検証
            $response->assertRedirect('/attachment/apply/show/' . $targetApply->id);
        }
    }

    public function ownerCanChoseTypeStatusDataProvider(): array
    {
        return [
            "事前相談の場合許可" => [ApplyStatuses::PRIOR_CONSULTATION, 302],
            "作成中の場合許可" => [ApplyStatuses::CREATING_DOCUMENT, 302],
            "確認中の場合許可（編集ロック機能により制御）" => [ApplyStatuses::CHECKING_DOCUMENT, 302],
            "提出中の場合許可（編集ロック機能により制御）" => [ApplyStatuses::SUBMITTING_DOCUMENT, 302],
            "審査中の場合禁止" => [ApplyStatuses::UNDER_REVIEW, 403],
            "応諾の場合禁止" => [ApplyStatuses::ACCEPTED, 403],
            "中止の場合禁止" => [ApplyStatuses::CANCEL, 403],
        ];
    }

    /**
     * test_SecretariatCanChooseType
     *
     * @covers \App\Http\Controllers\Attachment\Apply\ChooseTypeController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_SecretariatCanChooseType()
    {
        //窓口側アカウントであるユーザ102
        $secretariatActor = User::find(102);
        $parameter = ['type' => []];
        $response = $this->actingAs($secretariatActor)->post('/attachment/apply/choose_type/1', $parameter);
        $response->assertStatus(302);
        $response->assertRedirect('/attachment/apply/show/1');
    }

    /**
     * test_OtherApplicantCanNotChoseType
     *
     * @covers \App\Http\Controllers\Attachment\Apply\ChooseTypeController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OtherApplicantCanNotChoseType()
    {
        //アクセスするユーザーの作成
        $actorOtherApplicant = User::factory()->state(['role_id' => 3])->create();
        //申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        /** @var ApplyStatuses $statuses */
        $statuses = app(ApplyStatuses::class);

        //全てのステータスについて検証。
        foreach ($statuses as $statusId => $status) {
            $targetApply = Apply::factory()->state([
                'user_id' => $applicantOwner->id,
                'status' => $statusId,
            ])->create();

            //申出の作成者ではないユーザーでアクセスした場合、403となる
            $response = $this->actingAs($actorOtherApplicant)->post(
                '/attachment/apply/choose_type/' . $targetApply->id,
                []
            );
            $response->assertStatus(403);
        }
    }


    /**
     * test_SecretariatChooseType
     * 実際に種別を変更するテスト
     *
     * @covers \App\Http\Controllers\Attachment\Apply\ChooseTypeController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_SecretariatChooseType()
    {
        //窓口側アカウントであるユーザ102
        $secretariatActor = User::find(102);
        //添付ファイル1の種別を101に変更
        $parameter = ['type' => [1 => 101]];
        $response = $this->actingAs($secretariatActor)->post('/attachment/apply/choose_type/1', $parameter);
        $response->assertStatus(302);
        $response->assertRedirect('/attachment/apply/show/1');
    }
}
