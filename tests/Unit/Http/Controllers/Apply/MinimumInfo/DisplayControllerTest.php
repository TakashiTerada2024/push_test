<?php

namespace Tests\Unit\Http\Controllers\Apply\MinimumInfo;

use App\Http\Controllers\Apply\MinimumInfo\DisplayController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Mockery;
use Tests\TestCase;

class DisplayControllerTest extends TestCase
{
    use RefreshDatabase;

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DisplayController();

        // Viewファサードをモック化
        View::shouldReceive('make')
            ->andReturnUsing(function ($view, $data) {
                return ['view' => $view, 'data' => $data];
            });
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_正常に最低限必要な情報登録画面が表示されること()
    {
        // テストデータ
        $skipData = [
            'apply_type_id' => 1,
            'apply_type_name' => 'テスト申請種別'
        ];

        // セッションデータをモック
        session(['skip_preliminary_data' => $skipData]);

        // コントローラーを実行
        $result = $this->controller->__invoke();

        // 結果の検証
        $this->assertEquals('contents.apply.minimum-info', $result['view']);
        $this->assertEquals($skipData, $result['data']['skipData']);
    }

    public function test_セッションデータがない場合でも画面が表示されること()
    {
        // セッションデータを設定しない（初期状態はnull）
        session()->forget('skip_preliminary_data');

        // コントローラーを実行
        $result = $this->controller->__invoke();

        // 結果の検証
        $this->assertEquals('contents.apply.minimum-info', $result['view']);
        $this->assertNull($result['data']['skipData']);
    }

    public function test_適切なログが出力されること()
    {
        // テストデータ
        $userId = 1;
        $skipData = [
            'apply_type_id' => 1,
            'apply_type_name' => 'テスト申請種別'
        ];

        // 認証ユーザーをモック
        $this->actingAs($this->createTestUser($userId));

        // セッションデータをモック
        session(['skip_preliminary_data' => $skipData]);

        // ログ出力をモック（alias:を使わない）
        Log::shouldReceive('info')
            ->once()
            ->with('Displaying minimum info registration form', [
                'user_id' => $userId,
                'skip_data' => $skipData,
            ]);

        // コントローラーを実行
        $this->controller->__invoke();

        // 追加のアサーションを明示的に記録
        $this->addToAssertionCount(1);
    }

    /**
     * テスト用のユーザーを作成
     *
     * @param int $userId
     * @return mixed
     */
    private function createTestUser(int $userId)
    {
        return new class ($userId) implements \Illuminate\Contracts\Auth\Authenticatable {
            private $userId;

            public function __construct($userId)
            {
                $this->userId = $userId;
            }

            public function getAuthIdentifierName()
            {
                return 'id';
            }
            public function getAuthIdentifier()
            {
                return $this->userId;
            }
            public function getAuthPassword()
            {
                return 'dummy';
            }
            public function getRememberToken()
            {
                return null;
            }
            public function setRememberToken($value)
            {
            }
            public function getRememberTokenName()
            {
                return 'remember_token';
            }
        };
    }
}
