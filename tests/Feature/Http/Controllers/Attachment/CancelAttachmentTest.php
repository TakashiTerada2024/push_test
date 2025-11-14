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

namespace Tests\Feature\Http\Controllers\Attachment;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Tests\Feature\FeatureTestBase;

/**
 * Class CancelAttachmentTest
 * 添付ファイル提出キャンセルに関する機能テスト
 *
 * @package Tests\Feature\Http\Controllers\Attachment
 */
class CancelAttachmentTest extends FeatureTestBase
{
    /**
     * test_ownerCancelAttachment
     *
     * @param int $attachmentStatus
     * @param int $expectedStatus
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider attachmentStatusDataProvider
     */
    public function test_ownerCancelAttachment(int $attachmentStatus, int $expectedStatus)
    {
        // ユーザー作成
        // テスト用申出者の作成
        $user = User::factory()->state(['role_id' => 3])->create();

        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $user->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $user->id,
            'apply_id' => $targetApply->id,
            'status' => $attachmentStatus
        ])->create();

        $route = route('attachment.apply.cancel', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $response = $this->actingAs($user)->get($route);

        $response->assertStatus($expectedStatus);
    }

    /**
     * attachmentStatusDataProvider
     *
     * @return array
     */
    public function attachmentStatusDataProvider(): array
    {
        return [
            "申出者_アップロードの場合403" => [AttachmentStatuses::UPLOADED, 403],
            "申出者_提出済の場合302" => [AttachmentStatuses::SUBMITTING, 302],
            "申出者_承認済の場合403" => [AttachmentStatuses::APPROVED, 403],
        ];
    }

    /**
     * test_otherApplicantCannotCancelAttachment
     *
     * @param int $attachmentStatus
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider attachmentStatusDataProvider
     */
    public function test_otherApplicantCannotCancelAttachment(int $attachmentStatus)
    {
        // ユーザー作成
        // テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();
        $otherApplicant = User::factory()->state(['role_id' => 3])->create();


        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => $attachmentStatus
        ])->create();

        $route = route('attachment.apply.cancel', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);

        // 所有者と異なる申出者によるアクセスをテスト
        $response = $this->actingAs($otherApplicant)->get($route);

        $response->assertStatus(403);
    }

    /**
     * test_adminCancelAttachment
     *
     * @param int $attachmentStatus
     * @param int $expectedStatus
     * @param int $roleId
     * @author m.shomura <m.shomura@balocco.info>
     * @dataProvider adminAttachmentStatusDataProvider
     */
    public function test_adminCancelAttachment(int $attachmentStatus, int $expectedStatus, int $roleId)
    {
        // ユーザー作成
        // テスト用申出者の作成
        $user = User::factory()->state(['role_id' => $roleId])->create();

        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $user->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $user->id,
            'apply_id' => $targetApply->id,
            'status' => $attachmentStatus
        ])->create();

        $route = route('attachment.apply.cancel', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $response = $this->actingAs($user)->get($route);

        $response->assertStatus($expectedStatus);
    }

    /**
     * adminAttachmentStatusDataProvider
     *
     * @return array
     */
    public function adminAttachmentStatusDataProvider(): array
    {
        return [
            "管理者_アップロードの場合403" => [AttachmentStatuses::UPLOADED, 403, 1],
            "管理者_提出済の場合302" => [AttachmentStatuses::SUBMITTING, 302, 1],
            "管理者_承認済の場合403" => [AttachmentStatuses::APPROVED, 403, 1],
            "事務局_アップロードの場合403" => [AttachmentStatuses::UPLOADED, 403, 2],
            "事務局_提出済の場合302" => [AttachmentStatuses::SUBMITTING, 302, 2],
            "事務局_承認済の場合403" => [AttachmentStatuses::APPROVED, 403, 2],
        ];
    }
}
