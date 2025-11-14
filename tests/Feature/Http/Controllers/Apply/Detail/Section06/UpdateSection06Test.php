<?php

namespace Tests\Feature\Http\Controllers\Apply\Detail\Section06;

use App\Models\User;
use App\Models\Apply;
use App\Models\ScreenLock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\ScreenLocks;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;

class UpdateSection06Test extends TestCase
{
    use RefreshDatabase;

    private const ROLE_SECRETARY = 2;  // 事務局は2
    private const ROLE_APPLICANT = 3;  // 申出者は3

    private ScreenLocks $screenLocks;

    protected function setUp(): void
    {
        parent::setUp();
        $this->screenLocks = new ScreenLocks();
    }

    /**
     * @dataProvider successfulUpdateDataProvider
     */
    public function test_can_update_section06_when_conditions_are_met(string $userRole, ?bool $isLocked, int $status)
    {
        // Arrange
        $user = User::factory()->create([
            'role_id' => $userRole === 'secretary' ? self::ROLE_SECRETARY : self::ROLE_APPLICANT
        ]);
        $apply = Apply::factory()->create([
            'status' => $status,
            'user_id' => $user->id,
            'type_id' => ApplyTypes::CIVILIAN_STATISTICS
        ]);

        if ($isLocked !== null) {
            $this->lockSection06($apply->id, $isLocked);
        }

        $this->actingAs($user);

        // Act
        $response = $this->post("/apply/detail/section6/{$apply->id}", [
            '6_usage_period_end' => '2024-12-31',
            '6_research_period_start' => '2024-01-01',
            '6_research_period_end' => '2024-12-31'
        ]);

        // Assert
        $response->assertStatus(302)
                ->assertRedirect(route('apply.detail.section6', ['applyId' => $apply->id]));
    }

    /**
     * @dataProvider failedUpdateDataProvider
     */
    public function test_cannot_update_section06_when_conditions_are_not_met(string $userRole, ?bool $isLocked, int $status)
    {
        // Arrange
        $user = User::factory()->create([
            'role_id' => $userRole === 'secretary' ? self::ROLE_SECRETARY : self::ROLE_APPLICANT
        ]);
        $apply = Apply::factory()->create([
            'status' => $status,
            'user_id' => $user->id,
            'type_id' => ApplyTypes::CIVILIAN_STATISTICS
        ]);

        if (!is_null($isLocked)) {
            $this->lockSection06($apply->id, $isLocked);
        }

        $this->actingAs($user);

        // Act
        $response = $this->post("/apply/detail/section6/{$apply->id}", [
            '6_usage_period_end' => '2024-12-31',
            '6_research_period_start' => '2024-01-01',
            '6_research_period_end' => '2024-12-31'
        ]);

        // Assert
        $response->assertStatus(403);
    }

    public function successfulUpdateDataProvider(): array
    {
        return [
            // ロックなしの場合は更新可能
            '申出者_ロックなし_作成中' => ['applicant', false, ApplyStatuses::CREATING_DOCUMENT],
            '申出者_ロックなし_確認中' => ['applicant', false, ApplyStatuses::CHECKING_DOCUMENT],
            '申出者_ロックなし_提出中' => ['applicant', false, ApplyStatuses::SUBMITTING_DOCUMENT],

            // ロック未設定の場合も更新可能
            '申出者_ロック未設定_作成中' => ['applicant', null, ApplyStatuses::CREATING_DOCUMENT],
            '申出者_ロック未設定_確認中' => ['applicant', null, ApplyStatuses::CHECKING_DOCUMENT],
            '申出者_ロック未設定_提出中' => ['applicant', null, ApplyStatuses::SUBMITTING_DOCUMENT],
        ];
    }

    public function failedUpdateDataProvider(): array
    {
        return [
            // 事務局ユーザーの場合（ロック状態に関わらず403）
            '事務局_ロックあり_作成中' => ['secretary', true, ApplyStatuses::CREATING_DOCUMENT],
            '事務局_ロックあり_確認中' => ['secretary', true, ApplyStatuses::CHECKING_DOCUMENT],
            '事務局_ロックあり_提出中' => ['secretary', true, ApplyStatuses::SUBMITTING_DOCUMENT],
            '事務局_ロックなし_作成中' => ['secretary', false, ApplyStatuses::CREATING_DOCUMENT],
            '事務局_ロックなし_確認中' => ['secretary', false, ApplyStatuses::CHECKING_DOCUMENT],
            '事務局_ロックなし_提出中' => ['secretary', false, ApplyStatuses::SUBMITTING_DOCUMENT],
            '事務局_ロック未設定_作成中' => ['secretary', null, ApplyStatuses::CREATING_DOCUMENT],
            '事務局_ロック未設定_確認中' => ['secretary', null, ApplyStatuses::CHECKING_DOCUMENT],
            '事務局_ロック未設定_提出中' => ['secretary', null, ApplyStatuses::SUBMITTING_DOCUMENT],

            // 申出者でロックありの場合は更新不可
            '申出者_ロックあり_作成中' => ['applicant', true, ApplyStatuses::CREATING_DOCUMENT],
            '申出者_ロックあり_確認中' => ['applicant', true, ApplyStatuses::CHECKING_DOCUMENT],
            '申出者_ロックあり_提出中' => ['applicant', true, ApplyStatuses::SUBMITTING_DOCUMENT],

            // その他のステータスの場合（ロック状態に関係なく）
            '事務局_ロックあり_審査中' => ['secretary', true, ApplyStatuses::UNDER_REVIEW],
            '事務局_ロックなし_審査中' => ['secretary', false, ApplyStatuses::UNDER_REVIEW],
            '事務局_ロック未設定_審査中' => ['secretary', null, ApplyStatuses::UNDER_REVIEW],

            '事務局_ロックあり_応諾' => ['secretary', true, ApplyStatuses::ACCEPTED],
            '事務局_ロックなし_応諾' => ['secretary', false, ApplyStatuses::ACCEPTED],
            '事務局_ロック未設定_応諾' => ['secretary', null, ApplyStatuses::ACCEPTED],

            '申出者_ロックあり_審査中' => ['applicant', true, ApplyStatuses::UNDER_REVIEW],
        ];
    }

    private function lockSection06(int $applyId, bool $locked = true): void
    {
        if ($locked === null) {
            return;
        }

        ScreenLock::factory()->create([
            'apply_id' => $applyId,
            'screen_code' => 'section6',
            'is_locked' => $locked,
            'last_updated_by' => auth()->id() ?? 1
        ]);
    }
}
