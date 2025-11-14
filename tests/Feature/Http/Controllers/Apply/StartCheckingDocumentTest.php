<?php

namespace Tests\Feature\Http\Controllers\Apply;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\AttachmentLock;
use App\Models\ScreenLock;
use App\Models\User;
use App\Notifications\StartCheckingDocumentNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestBase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * StartCheckingDocumentTest
 * 申出を確認中に変更する機能のテスト。
 * この機能は、事務局アカウントでのみ利用可能である。
 * @see \Ncc01\Apply\Enterprise\Classification\ApplyStatuses
 */
class StartCheckingDocumentTest extends FeatureTestBase
{
    /**
     * 申出者アカウントは申出文書を確認中へ変更することが可能である。
     * この際、ロック状態はすべてtrueに変更される。
     * （事務局による確認中に、申し出者が変更しないようにするため）
     */
    public function testApplicantCanStartChecking()
    {
        Notification::fake();
        $applicant = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->creating()->state([
            "user_id" => $applicant->id,
            "status" => ApplyStatuses::CREATING_DOCUMENT
        ])->create();

        // ロック状態をfalseで作成
        ScreenLock::factory()->createForAllScreens($apply->id, $applicant->id, false);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $applicant->id, false);

        // アクセス
        $response = $this->actingAs($applicant)->post("/apply/start_checking_document/" . $apply->id);

        // マネージャーであるため、成功後のリダイレクト302が返却される
        $this->assertSame(302, $response->status());

        // Applyのステータスが変化していることを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CHECKING_DOCUMENT,
        ]);

        // ScreenLockのロック状態がすべてtrue（変更されていること）を検証
        $this->assertDatabaseHas('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);
        $this->assertDatabaseMissing('screen_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);

        // AttachmentLockのロック状態がすべてtrue（変更されていること）を検証
        $this->assertDatabaseHas('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => true,
        ]);
        $this->assertDatabaseMissing('attachment_locks', [
            'apply_id' => $apply->id,
            'is_locked' => false,
        ]);

        // 通知が行われることを検証
        Notification::assertTimesSent(1, StartCheckingDocumentNotification::class);
    }

    public function testOtherApplicantCannotStartChecking()
    {
        $otherApplicant = User::factory()->state(["role_id" => 3])->create();
        $owner = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->creating()->state([
            "user_id" => $owner->id,
            "status" => ApplyStatuses::CREATING_DOCUMENT
        ])->create();

        // ロック状態をfalseで作成
        ScreenLock::factory()->createForAllScreens($apply->id, $owner->id, false);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $owner->id, false);

        // アクセス
        $response = $this->actingAs($otherApplicant)->post("/apply/start_checking_document/" . $apply->id);

        // 申請者本人ではないため、403が返却される
        $this->assertSame(403, $response->status());
    }


    public function testSecretariatCanNotStartChecking()
    {
        $secretariat = User::factory()->state(["role_id" => 2])->create();
        $apply = Apply::factory()->creating()->state([
            "user_id" => $secretariat->id,
            "status" => ApplyStatuses::CREATING_DOCUMENT
        ])->create();
        ScreenLock::factory()->createForAllScreens($apply->id, $secretariat->id, false);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $secretariat->id, false);

        // アクセス
        $response = $this->actingAs($secretariat)->post("/apply/start_checking_document/" . $apply->id);

        // 申請者であるため、403が返却される
        $this->assertSame(403, $response->status());

        // Applyのステータスが変化していないことを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CREATING_DOCUMENT,
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
}
