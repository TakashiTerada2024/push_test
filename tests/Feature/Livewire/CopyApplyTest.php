<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\CopyApply;
use App\Models\User;
use App\Models\Apply;
use App\Models\ApplyHistory;
use Livewire\Livewire;
use Tests\TestCase;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

class CopyApplyTest extends TestCase
{
    public function test_apply_request_is_not_exists()
    {
        $component = Livewire::test(CopyApply::class, ['applyId' => time()])->call('cloneApply');
        $component->assertNotFound();
    }

    /**
     * 変更申出を作成するテスト
     * このテストはうまく動かすのが難しい
     * そもそもLivewireで実装されていることが問題。
     * 根本的な原因解決まで、このテストは動けばOK
     */
    public function test_apply_request_is_success()
    {
        $applicantOwner = User::factory()->state(['role_id' => 2])->create();
        $apply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => ApplyStatuses::ACCEPTED,
            'accepted_at' => now()
        ])->create();

        $this->actingAs($applicantOwner);
        session()->start();
        Livewire::test(CopyApply::class, ['applyId' => $apply->id])
            ->call('cloneApply');

        // 新しい申請を取得
        $applyHistory = ApplyHistory::where('source_apply_id', $apply->id)->first();
        $clonedApply = Apply::find($applyHistory->apply_id);

        // 申請のアサーション
        $this->assertNotNull($clonedApply, '新しい申請が作成されていません');
        $this->assertEquals(
            ApplyStatuses::CREATING_DOCUMENT,
            $clonedApply->status,
            '新しい申請のステータスが正しくありません'
        );
        $this->assertEmpty($clonedApply->accepted_at, 'accepted_atが空ではありません');
    }
}
