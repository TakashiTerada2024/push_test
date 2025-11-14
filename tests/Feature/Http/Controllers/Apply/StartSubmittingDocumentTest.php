<?php

namespace Tests\Feature\Http\Controllers\Apply;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\AttachmentLock;
use App\Models\ScreenLock;
use App\Models\User;
use App\Notifications\StartSubmittingDocumentNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestBase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * StartSubmittingDocumentControllerTest
 *
 * @see \Ncc01\Apply\Enterprise\Classification\ApplyStatuses
 */
class StartSubmittingDocumentTest extends FeatureTestBase
{
    public function testApplicantCanNotSubmit()
    {
        $applicant = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->checking()->state(["user_id" => $applicant->id])->create();
        ScreenLock::factory()->createForAllScreens($apply->id, $applicant->id, false);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $applicant->id, false);

        //アクセス
        $response = $this->actingAs($applicant)->post("/apply/start_submitting_document/" . $apply->id);

        //申請者であるため、403が返却される
        $this->assertSame(403, $response->status());

        // Applyのステータスが変化していないことを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CHECKING_DOCUMENT,
        ]);

        // ScreenLockのロック状態がすべてfalse（変更されていないこと）を検証
        $this->assertDatabaseHas('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);
        $this->assertDatabaseMissing('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);

        // AttachmentLockのロック状態がすべてfalse（変更されていないこと）を検証
        $this->assertDatabaseHas('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);
        $this->assertDatabaseMissing('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);
    }

    /**
     * 事務局アカウントは申出文書 提出中へのステータス更新が可能である。
     * この際、ロック状態はすべてtrueに変更される。
     */
    public function testSecretariatCanSubmit()
    {
        Notification::fake();

        $manager = User::factory()->state(["role_id" => 2])->create();
        $applicant = User::factory()->state(["role_id" => 3])->create();

        $apply = Apply::factory()->checking()->state(["user_id" => $applicant->id])->create();
        ScreenLock::factory()->createForAllScreens($apply->id, $manager->id, false);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $manager->id, false);

        // アクセス
        $response = $this->actingAs($manager)->post("/apply/start_submitting_document/" . $apply->id);

        // マネージャーであるため、成功後のリダイレクト302が返却される
        $this->assertSame(302, $response->status());

        // Applyのステータスが変化していることを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::SUBMITTING_DOCUMENT,
        ]);

        // ScreenLockのロック状態がすべてtrue（変更されていること）を検証
        $this->assertDatabaseHas('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);

        // AttachmentLockのロック状態がすべてtrue（変更されていること）を検証
        $this->assertDatabaseHas('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);

        // 申し出者に対する通知が行われることを検証
        Notification::assertSentTo([$applicant], StartSubmittingDocumentNotification::class);
    }
}
