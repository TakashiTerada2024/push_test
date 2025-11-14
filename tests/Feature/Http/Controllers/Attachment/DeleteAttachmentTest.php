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

namespace Tests\Feature\Http\Controllers\Attachment;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\User;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Tests\Feature\FeatureTestBase;

/**
 * Class DeleteAttachmentTest
 * 添付ファイル削除に関する機能テスト
 *
 * @package Tests\Feature\Http\Controllers\Attachment
 */
class DeleteAttachmentTest extends FeatureTestBase
{
    /**
     * test_ownerCanDeleteAttachment
     *
     * @param int $applyStatus
     * @param int $expectedStatus
     * @param int $attachmentStatus
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @dataProvider applyStatusDataProvider
     */
    public function test_ownerCanDeleteAttachment(int $applyStatus, int $expectedStatus, int $attachmentStatus)
    {
        //ユーザー作成
        //テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatus,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => $attachmentStatus
        ])->create();

        $route = route('attachment.apply.delete', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $response = $this->actingAs($applicantOwner)->get($route);

        $response->assertStatus($expectedStatus);
    }

    /**
     * applyStatusDataProvider
     *
     * @return array
     */
    public function applyStatusDataProvider(): array
    {
        return [
            [ApplyStatuses::PRIOR_CONSULTATION, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CREATING_DOCUMENT, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CHECKING_DOCUMENT, 403, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::SUBMITTING_DOCUMENT, 403, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::UNDER_REVIEW, 403, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::ACCEPTED, 403, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CANCEL, 403, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::PRIOR_CONSULTATION, 403, AttachmentStatuses::SUBMITTING],
            [ApplyStatuses::CREATING_DOCUMENT, 403, AttachmentStatuses::APPROVED],
        ];
    }

    /**
     * test_otherApplicantCannotDeleteAttachment
     *
     * @param int $applyStatus
     * @dataProvider applyStatusDataProvider
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_otherApplicantCannotDeleteAttachment(int $applyStatus)
    {
        //ユーザー作成
        //テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();
        $otherApplicant = User::factory()->state(['role_id' => 3])->create();


        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatus,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
        ])->create();

        $route = route('attachment.apply.delete', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);

        //所有者と異なる申出者によるアクセスをテスト
        $response = $this->actingAs($otherApplicant)->get($route);

        $response->assertStatus(403);
    }

    /**
     * test_secretariatCannotDeleteAttachment
     *
     * @param int $applyStatus
     * @param int $expectedStatus
     * @param int $attachmentStatus
     * @dataProvider secretariatApplyStatusDataProvider
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_secretariatCannotDeleteAttachment(
        int $applyStatus,
        int $expectedStatus,
        int $attachmentStatus
    ) {
        $secretariat = User::factory()->state(['role_id' => 2])->create();

        //テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();
        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => $applyStatus,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => $attachmentStatus
        ])->create();

        $route = route('attachment.apply.delete', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);

        // 事務局によるアクセスをテスト
        $response = $this->actingAs($secretariat)->get($route);
        $response->assertStatus($expectedStatus);
    }

    /**
     * secretariatApplyStatusDataProvider
     *
     * @return array
     */
    public function secretariatApplyStatusDataProvider(): array
    {
        return [
            [ApplyStatuses::PRIOR_CONSULTATION, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CREATING_DOCUMENT, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CHECKING_DOCUMENT, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::SUBMITTING_DOCUMENT, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::UNDER_REVIEW, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::ACCEPTED, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::CANCEL, 302, AttachmentStatuses::UPLOADED],
            [ApplyStatuses::PRIOR_CONSULTATION, 403, AttachmentStatuses::SUBMITTING],
            [ApplyStatuses::CREATING_DOCUMENT, 403, AttachmentStatuses::APPROVED],
        ];
    }
}
