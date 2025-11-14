<?php

namespace Tests\Unit\Http\Controllers\Apply;

use App\Http\Controllers\Apply\SkipPreliminaryController;
use App\Services\Apply\SkipPreliminaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Mockery;

class SkipPreliminaryControllerTest extends TestCase
{
    private SkipPreliminaryController $controller;
    private $skipPreliminaryServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // サービスのモック作成
        $this->skipPreliminaryServiceMock = Mockery::mock(SkipPreliminaryService::class);

        // コントローラ作成
        $this->controller = new SkipPreliminaryController($this->skipPreliminaryServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function 無効なULIDが渡された場合はウェルカムページにエラーメッセージとともにリダイレクトされること()
    {
        // 準備
        $ulid = 'invalid-ulid';
        $request = new Request();

        // サービスのモックが無効なトークンを返すよう設定
        $this->skipPreliminaryServiceMock
            ->shouldReceive('validateToken')
            ->once()
            ->with($ulid)
            ->andReturn(null);

        // 実行
        $response = $this->controller->handle($request, $ulid);

        // 検証
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('welcome'), $response->getTargetUrl());
        $this->assertEquals('無効なURLです。事前相談スキップ用のURLを再度確認してください。', session('error'));
    }

    /**
     * @test
     */
    public function 有効なULIDでログイン済み事務局ユーザーの場合は申出検索画面にリダイレクトされること()
    {
        // 準備
        $ulid = 'valid-ulid';
        $request = new Request();
        $tokenData = ['some' => 'data'];

        // サービスのモックが有効なトークンを返すよう設定
        $this->skipPreliminaryServiceMock
            ->shouldReceive('validateToken')
            ->once()
            ->with($ulid)
            ->andReturn($tokenData);

        // stdClassを使って単純なユーザーオブジェクトを作成
        $user = new \stdClass();
        $user->role_id = 2;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn(1);

        // 実行
        $response = $this->controller->handle($request, $ulid);

        // 検証
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('apply.lists.search'), $response->getTargetUrl());
        $this->assertEquals('事務局ユーザーは申出スキップURLを使用できません。', session('info'));
    }

    /**
     * @test
     */
    public function 有効なULIDでログイン済み一般ユーザーの場合は最低限必要な情報登録画面にリダイレクトされること()
    {
        // 準備
        $ulid = 'valid-ulid';
        $request = new Request();
        $tokenData = ['some' => 'data'];

        // サービスのモックが有効なトークンを返すよう設定
        $this->skipPreliminaryServiceMock
            ->shouldReceive('validateToken')
            ->once()
            ->with($ulid)
            ->andReturn($tokenData);

        // stdClassを使って単純なユーザーオブジェクトを作成
        $user = new \stdClass();
        $user->role_id = 1;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn(1);

        // 実行
        $response = $this->controller->handle($request, $ulid);

        // 検証
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('apply.minimum-info.create'), $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function 有効なULIDで未ログインの場合はログイン画面にリダイレクトされ意図したURLが設定されること()
    {
        // 準備
        $ulid = 'valid-ulid';
        // Requestオブジェクトをモックに変更
        $request = Mockery::mock(Request::class);
        $tokenData = ['some' => 'data'];

        // サービスのモックが有効なトークンを返すよう設定
        $this->skipPreliminaryServiceMock
            ->shouldReceive('validateToken')
            ->once()
            ->with($ulid)
            ->andReturn($tokenData);

        // 未ログイン状態を模擬
        Auth::shouldReceive('check')->andReturn(false);

        // セッションのモック
        $sessionMock = Mockery::mock();
        $sessionMock->shouldReceive('put')->once()->with('url.intended', route('apply.minimum-info.create'));
        $request->shouldReceive('session')->andReturn($sessionMock);

        // 実行
        $response = $this->controller->handle($request, $ulid);

        // 検証
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('login'), $response->getTargetUrl());
    }
}
