<?php

namespace Tests\Feature\Http\Controllers\Apply;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\AttachmentLock;
use App\Models\ScreenLock;
use App\Models\User;
use App\Notifications\StartCreatingDocumentNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestBase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * StartCreatingDocumentTest
 * 申出を作成中に変更する機能のテスト。
 * この機能は、事務局アカウントでのみ利用可能である。
 * @see \Ncc01\Apply\Enterprise\Classification\ApplyStatuses
 */
class StartCreatingDocumentTest extends FeatureTestBase
{
    public function testApplicantCanNotStartCreating()
    {
        $applicant = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->checking()->state([
            "user_id" => $applicant->id,
            "status" => ApplyStatuses::PRIOR_CONSULTATION
        ])->create();
        ScreenLock::factory()->createForAllScreens($apply->id, $applicant->id, true);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $applicant->id, true);

        // アクセス
        $response = $this->actingAs($applicant)->post("/apply/start_creating_document/" . $apply->id);

        // 申請者であるため、403が返却される
        $this->assertSame(403, $response->status());

        // Applyのステータスが変化していないことを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::PRIOR_CONSULTATION,
        ]);

        // ScreenLockのロック状態がすべてtrue（変更されていないこと）を検証
        $this->assertDatabaseHas('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);
        $this->assertDatabaseMissing('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);

        // AttachmentLockのロック状態がすべてtrue（変更されていないこと）を検証
        $this->assertDatabaseHas('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);
        $this->assertDatabaseMissing('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);
    }

    /**
     * 事務局アカウントは申出文書を作成中へ変更することが可能である。
     * この際、ロック状態はすべてfalseに変更される。
     */
    public function testSecretariatCanStartCreating()
    {
        Notification::fake();
        $manager = User::factory()->state(["role_id" => 2])->create();
        $apply = Apply::factory()->checking()->state([
            "user_id" => $manager->id,
            "status" => ApplyStatuses::PRIOR_CONSULTATION
        ])->create();

        // ロック状態をtrueで作成
        ScreenLock::factory()->createForAllScreens($apply->id, $manager->id, true);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $manager->id, true);

        // アクセス
        $response = $this->actingAs($manager)->post("/apply/start_creating_document/" . $apply->id);

        // マネージャーであるため、成功後のリダイレクト302が返却される
        $this->assertSame(302, $response->status());

        // Applyのステータスが変化していることを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CREATING_DOCUMENT,
        ]);

        // ScreenLockのロック状態がすべてfalse（変更されていること）を検証
        $this->assertDatabaseHas('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);
        $this->assertDatabaseMissing('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);

        // AttachmentLockのロック状態がすべてfalse（変更されていること）を検証
        $this->assertDatabaseHas('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);
        $this->assertDatabaseMissing('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);

        //通知が行われることを検証
        Notification::assertTimesSent(1, StartCreatingDocumentNotification::class);
    }
}
