<?php

namespace Tests\Feature\Http\Controllers\Apply\Lock;

use App\Models\Apply;
use App\Models\ScreenLock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScreenLockControlTest extends TestCase
{
    use RefreshDatabase;

    private User $applicantUser;
    private User $secretariatUser;
    private Apply $apply;

    protected function setUp(): void
    {
        parent::setUp();

        // テストユーザーの作成
        $this->applicantUser = User::factory()->create(['role_id' => 3]); // 申請者
        $this->secretariatUser = User::factory()->create(['role_id' => 2]); // 事務局

        // テスト用申請の作成
        $this->apply = Apply::factory()->creating()->state(['user_id' => $this->applicantUser->id])->create();
    }

    /**
     * @test
     * @dataProvider screenRouteProvider
     */
    public function 申請者がロック状態の画面にアクセスした場合に警告のメッセージが表示される(string $routeName)
    {
        // 準備：画面をロック状態にする
        ScreenLock::factory()->create([
            'apply_id' => $this->apply->id,
            'screen_code' => $this->getScreenIdFromRoute($routeName),
            'is_locked' => true
        ]);

        // 実行：申請者としてアクセス
        $response = $this->actingAs($this->applicantUser)
            ->get(route($routeName, ['applyId' => $this->apply->id]));

        // 検証：200 アクセスが成功する
        $response->assertStatus(200);
        // 検証：ロック状態の警告メッセージが表示される
        $response->assertSee('このセクションは現在ロックされています');
    }

    /**
     * @test
     * @dataProvider screenRouteProvider
     */
    public function 申請者がアンロック状態の画面にアクセスした場合に警告のメッセージが表示されない(string $routeName)
    {
        // 準備：画面をロック状態にする
        ScreenLock::factory()->create([
            'apply_id' => $this->apply->id,
            'screen_code' => $this->getScreenIdFromRoute($routeName),
            'is_locked' => false
        ]);

        // 実行：申請者としてアクセス
        $response = $this->actingAs($this->applicantUser)
            ->get(route($routeName, ['applyId' => $this->apply->id]));

        // 検証：200 アクセスが成功する
        $response->assertStatus(200);
        // 検証：ロック状態の警告メッセージが表示される
        $response->assertDontSee('このセクションは現在ロックされています');
    }

    /**
     * @test
     * @dataProvider screenRouteProvider
     */
    public function 事務局がロック状態の画面にアクセスした場合に警告のメッセージが表示される(string $routeName)
    {
        // 準備：画面をロック状態にする
        ScreenLock::factory()->create([
            'apply_id' => $this->apply->id,
            'screen_code' => $this->getScreenIdFromRoute($routeName),
            'is_locked' => true
        ]);

        // 実行：申請者としてアクセス
        $response = $this->actingAs($this->secretariatUser)
            ->get(route($routeName, ['applyId' => $this->apply->id]));

        // 検証：200 アクセスが成功する
        $response->assertStatus(200);
        // 検証：ロック状態の警告メッセージが表示される
        $response->assertSee('このセクションは現在ロックされています');
    }

    /**
     * @test
     * @dataProvider screenRouteProvider
     */
    public function 事務局がアンロック状態の画面にアクセスした場合に警告のメッセージが表示されない(string $routeName)
    {
        // 準備：画面をロック状態にする
        ScreenLock::factory()->create([
            'apply_id' => $this->apply->id,
            'screen_code' => $this->getScreenIdFromRoute($routeName),
            'is_locked' => false
        ]);

        // 実行：申請者としてアクセス
        $response = $this->actingAs($this->secretariatUser)
            ->get(route($routeName, ['applyId' => $this->apply->id]));

        // 検証：200 アクセスが成功する
        $response->assertStatus(200);
        // 検証：ロック状態の警告メッセージが表示される
        $response->assertDontSee('このセクションは現在ロックされています');
    }



    /**
     * テスト対象の画面ルートを提供するデータプロバイダー
     */
    public function screenRouteProvider(): array
    {
        return [
            'basic info' => ['apply.detail.basic.info'],
            'section1' => ['apply.detail.section1'],
            'section2' => ['apply.detail.section2'],
            'section3' => ['apply.detail.section3'],
            'section4' => ['apply.detail.section4'],
            'section5' => ['apply.detail.section5'],
            'section6' => ['apply.detail.section6'],
            'section7' => ['apply.detail.section7'],
            'section8' => ['apply.detail.section8'],
            'section9' => ['apply.detail.section9'],
            'section10' => ['apply.detail.section10'],
        ];
    }

    /**
     * ルート名からscreen_idを取得する
     */
    private function getScreenIdFromRoute(string $routeName): string
    {
        $screenIdMap = [
            'apply.detail.basic.info' => 'basic',
            'apply.detail.section1' => 'section1',
            'apply.detail.section2' => 'section2',
            'apply.detail.section3' => 'section3',
            'apply.detail.section4' => 'section4',
            'apply.detail.section5' => 'section5',
            'apply.detail.section6' => 'section6',
            'apply.detail.section7' => 'section7',
            'apply.detail.section8' => 'section8',
            'apply.detail.section9' => 'section9',
            'apply.detail.section10' => 'section10',
        ];

        return $screenIdMap[$routeName];
    }
}
