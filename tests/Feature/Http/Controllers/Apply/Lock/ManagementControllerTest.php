<?php

namespace Tests\Feature\Http\Controllers\Apply\Lock;

use App\Models\Apply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 事務局ユーザーはロック管理画面を表示できる()
    {
        // 準備
        $roleId = 2; // secretariat
        $user = User::factory()->create(['role_id' => $roleId]);
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $applyId = $apply->id;

        // 実行
        $response = $this->actingAs($user)
            ->get(route('lock.management', ['applyId' => $applyId]));

        // 検証
        $response->assertStatus(200)
            ->assertViewIs('contents.apply.lock.management')
            ->assertViewHas('applyId', $applyId);
    }

    /**
     * @test
     */
    public function 申し出者ユーザーはロック管理画面を表示できない()
    {
        // 準備
        $roleId = 3; // applicant
        $user = User::factory()->create(['role_id' => $roleId]);
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $applyId = $apply->id;

        // 実行
        $response = $this->actingAs($user)
            ->get(route('lock.management', ['applyId' => $applyId]));

        // 検証
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 事務局ユーザーが存在しない申請IDにアクセスすると404となる()
    {
        // 準備
        $roleId = 2; // secretariat
        $user = User::factory()->create(['role_id' => $roleId]);
        $nonExistentApplyId = '999999999';

        // 実行
        $response = $this->actingAs($user)
            ->get(route('lock.management', ['applyId' => $nonExistentApplyId]));

        // 検証
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function 未ログインユーザーはログイン画面にリダイレクトされる()
    {
        // 準備
        $applyId = '123456789';

        // 実行
        $response = $this->get(route('lock.management', ['applyId' => $applyId]));

        // 検証
        $response->assertRedirect(route('login'));
    }
}
