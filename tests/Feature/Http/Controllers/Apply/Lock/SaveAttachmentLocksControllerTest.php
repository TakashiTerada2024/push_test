<?php

namespace Tests\Feature\Http\Controllers\Apply\Lock;

use App\Models\Apply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveAttachmentLocksControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 事務局ユーザーは添付資料ロックを保存できる()
    {
        // 準備
        $roleId = 2; // secretariat
        $user = User::factory()->create(['role_id' => $roleId]);
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $applyId = $apply->id;
        $attachmentLocks = ['document1' => 'true', 'document2' => 'true'];

        // 実行
        $response = $this->actingAs($user)
            ->post(route('lock.management.attachment.save', ['applyId' => $applyId]), [
                'attachment_locks' => $attachmentLocks
            ]);

        // 検証
        $response->assertStatus(302)
            ->assertRedirect(route('lock.management', ['applyId' => $applyId]))
            ->assertSessionHas('message', '添付資料のロック状態を更新しました。');
    }

    /**
     * @test
     */
    public function 申し出者ユーザーは添付資料ロックを保存できない()
    {
        // 準備
        $roleId = 3; // applicant
        $user = User::factory()->create(['role_id' => $roleId]);
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $applyId = $apply->id;

        // 実行
        $response = $this->actingAs($user)
            ->post(route('lock.management.attachment.save', ['applyId' => $applyId]));

        // 検証
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 未ログインユーザーはログイン画面にリダイレクトされる()
    {
        // 準備
        $applyId = '123456789';

        // 実行
        $response = $this->post(route('lock.management.attachment.save', ['applyId' => $applyId]));

        // 検証
        $response->assertRedirect(route('login'));
    }
}
