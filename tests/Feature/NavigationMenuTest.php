<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Apply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ナビゲーションメニューの表示制御に関するテスト
 *
 * ユーザーロールと申請IDの有無に応じて、ロック制御メニューの表示が
 * 適切に制御されることを確認する。
 * - 事務局のみがロック制御メニューを表示できる
 * - ロック制御メニューは申請詳細画面でのみ表示される
 */
class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    private const ROLE_SUPER_ADMIN = 1;
    private const ROLE_SECRETARIAT = 2;
    private const ROLE_APPLICANT = 3;

    public function test_secretariat_can_see_lock_control_menu_with_apply_id()
    {
        $user = User::factory()->state(['role_id' => self::ROLE_SECRETARIAT])->create();
        $apply = Apply::factory()->creating()->state(['user_id' => $user->id])->create();
        $response = $this->actingAs($user)->get(route('apply.detail.overview', ['applyId' => $apply->id]));

        $response->assertStatus(200);
        $response->assertSee('ロック制御');
    }

    public function test_secretariat_cannot_see_lock_control_menu_without_apply_id()
    {
        $user = User::factory()->state(['role_id' => self::ROLE_SECRETARIAT])->create();
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('ロック制御');
    }

    public function test_applicant_cannot_see_lock_control_menu_with_apply_id()
    {
        $user = User::factory()->state(['role_id' => self::ROLE_APPLICANT])->create();
        $apply = Apply::factory()->creating()->state(['user_id' => $user->id])->create();
        $response = $this->actingAs($user)->get(route('apply.detail.overview', ['applyId' => $apply->id]));

        $response->assertStatus(200);
        $response->assertDontSee('ロック制御');
    }

    public function test_super_admin_cannot_see_lock_control_menu_with_apply_id()
    {
        $user = User::factory()->state(['role_id' => self::ROLE_SUPER_ADMIN])->create();
        $apply = Apply::factory()->creating()->state(['user_id' => $user->id])->create();
        $response = $this->actingAs($user)->get(route('apply.detail.overview', ['applyId' => $apply->id]));

        $response->assertStatus(200);
        $response->assertDontSee('ロック制御');
    }
}
