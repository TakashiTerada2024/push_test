<?php

namespace Tests\Feature\Http\Controllers\Apply;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\AttachmentLock;
use App\Models\ScreenLock;
use App\Models\User;
use App\Notifications\RemandCheckingDocumentNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\FeatureTestBase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * RemandCheckingDocumentTest
 *
 * @see \Ncc01\Apply\Enterprise\Classification\ApplyStatuses
 */
class RemandCheckingDocumentTest extends FeatureTestBase
{
    public function testApplicantCanNotRemand()
    {
        $applicant = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->checking()->state(["user_id" => $applicant->id])->create();
        ScreenLock::factory()->createForAllScreens($apply->id, $applicant->id, true);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $applicant->id, true);

        // アクセス
        $response = $this->actingAs($applicant)->post("/apply/remand_checking_document/" . $apply->id);

        // 申請者であるため、403が返却される
        $this->assertSame(403, $response->status());

        // Applyのステータスが変化していないことを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CHECKING_DOCUMENT,
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
     * 事務局アカウントは申出文書を作成中へ差し戻すことが可能である。
     * この際、ロック状態は変更されない。
     * 事務局が設定したロック状態がそのまま維持されなければならない。
     */
    public function testSecretariatCanRemand()
    {
        Notification::fake();

        $manager = User::factory()->state(["role_id" => 2])->create();
        $applicant = User::factory()->state(["role_id" => 3])->create();
        $apply = Apply::factory()->checking()->state(["user_id" => $applicant->id])->create();
        //1つの画面をtrue（ロック状態）それ以外はfalse（アンロック状態）
        ScreenLock::factory()->createForAllScreens($apply->id, $manager->id, 1);
        AttachmentLock::factory()->createForAllAttachments($apply->id, $manager->id, 1);

        // アクセス
        $response = $this->actingAs($manager)->post("/apply/remand_checking_document/" . $apply->id);

        // マネージャーであるため、成功後のリダイレクト302が返却される
        $this->assertSame(302, $response->status());

        // Applyのステータスが変化していることを検証
        $this->assertDatabaseHas('applies', [
            'id' => $apply->id,
            'status' => ApplyStatuses::CREATING_DOCUMENT,
        ]);

        // ScreenLockのロック状態が変更されていない(true,falseがどちらも存在している)ことを検証
        $this->assertDatabaseHas('screen_locks', ['apply_id' => $apply->id,'is_locked' => false,]);
        $this->assertDatabaseHas('screen_locks', ['apply_id' => $apply->id,'is_locked' => true,]);

        // AttachmentLockのロック状態がすべてfalse（変更されていること）を検証
        $this->assertDatabaseHas('attachment_locks', ['apply_id' => $apply->id,'is_locked' => false,]);
        $this->assertDatabaseHas('attachment_locks', ['apply_id' => $apply->id,'is_locked' => true,]);

        //申請者に対して通知が行われることを検証
        Notification::assertSentTo([$applicant], RemandCheckingDocumentNotification::class);
    }
}
