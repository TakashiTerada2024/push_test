<?php

namespace Tests\Unit\Http\Controllers\Apply\MinimumInfo;

use App\Http\Controllers\Apply\MinimumInfo\SaveController;
use App\Http\Requests\Apply\MinimumInfoRequest;
use App\Services\Apply\MinimumInfoApplyCreateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SaveControllerTest extends TestCase
{
    private $controller;
    private $applyCreateService;
    private $request;
    private $sessionManager;
    private $authManager;

    protected function setUp(): void
    {
        parent::setUp();

        // セッションのモック設定を変更
        $sessionStore = Mockery::mock('Illuminate\Session\Store');
        $this->sessionManager = Mockery::mock('Illuminate\Session\SessionManager');

        // 重要: セッションマネージャーが呼び出された時にセッションストアを返すようにする
        $this->sessionManager->shouldReceive('driver')->andReturn($sessionStore);
        $this->app->instance('session', $this->sessionManager);
        $this->app->instance('session.store', $sessionStore);

        // session()ヘルパー関数のモックも追加
        $this->app->bind('session', function () use ($sessionStore) {
            return $sessionStore;
        });

        // ★★★ 認証関連のモックを修正 ★★★
        $this->authManager = Mockery::mock('Illuminate\Auth\AuthManager');

        // id() メソッドを直接モック（追加）
        $this->authManager->shouldReceive('id')->andReturn(1);

        // 従来のguard関連のモックも残す
        $auth = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $auth->shouldReceive('id')->andReturn(1);
        $this->authManager->shouldReceive('guard')->andReturn($auth);

        $this->app->instance('auth', $this->authManager);

        $this->applyCreateService = Mockery::mock(MinimumInfoApplyCreateService::class);
        $this->request = Mockery::mock(MinimumInfoRequest::class);
        $this->controller = new SaveController($this->applyCreateService);
    }

    public function test_最低限必要な情報を保存して申出を作成できること()
    {
        // テストデータ
        $parameters = [
            'apply_type_id' => 1,
            'skip_url_id' => 'test-skip-url'
        ];
        $skipUrlUlid = 'test-ulid';
        $applyId = 1;

        // セッションのモックを修正 - セッションストアを使用するようにする
        $sessionStore = $this->app['session.store'];

        // session()ヘルパー関数のモックを正しく設定
        $sessionStore->shouldReceive('get')
            ->with('skip_preliminary_ulid', Mockery::any())
            ->andReturn($skipUrlUlid);

        // 重要: forgetメソッドのモック
        $sessionStore->shouldReceive('forget')
            ->with(['skip_preliminary_ulid', 'skip_preliminary_data'])
            ->once()
            ->andReturnNull();

        // フラッシュメッセージのモック - Redirectのwithメソッドで使用
        $sessionStore->shouldReceive('put')
            ->withAnyArgs()
            ->andReturnNull();

        // リクエストパラメータのモック
        $this->request->shouldReceive('getParameters')
            ->once()
            ->andReturn($parameters);

        // サービスのモック
        $this->applyCreateService->shouldReceive('createApplyFromMinimumInfo')
            ->with($parameters, $skipUrlUlid)
            ->once()
            ->andReturn($applyId);

        // DB操作のモック
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();  // 例外が発生しないことを明示

        // ログ出力のモック
        Log::shouldReceive('info')
            ->with('Created apply from minimum info', Mockery::on(function ($data) use ($applyId) {
                return isset($data['user_id']) &&
                       isset($data['apply_id']) &&
                       $data['apply_id'] === $applyId;
            }))
            ->once();
        Log::shouldReceive('error')->never();  // エラーログが出力されないことを明示

        // リダイレクト関連のモック
        $redirectResponse = Mockery::mock('Illuminate\Http\RedirectResponse');

        // withメソッドの期待値を汎用的に設定
        $redirectResponse->shouldReceive('with')
            ->withAnyArgs()  // どんな引数でも受け付ける
            ->andReturnSelf();

        // withInputメソッドのモック
        $redirectResponse->shouldReceive('withInput')
            ->andReturnSelf();

        // routeメソッドのモック
        Redirect::shouldReceive('route')
            ->with('apply.detail.overview', ['applyId' => $applyId])
            ->once()
            ->andReturn($redirectResponse);

        // backメソッドのモック
        Redirect::shouldReceive('back')
            ->andReturn($redirectResponse);

        // 実行
        $response = $this->controller->__invoke($this->request);

        // アサーション
        $this->assertSame($redirectResponse, $response);
    }

    public function test_スキップURL情報がない場合は申出一覧に戻されること()
    {
        // テストデータ
        $parameters = [
            'apply_type_id' => 1,
            'skip_url_id' => null
        ];

        // セッションストアのモック
        $sessionStore = $this->app['session.store'];
        $sessionStore->shouldReceive('get')
            ->with('skip_preliminary_ulid', Mockery::any())
            ->andReturn(null);

        // フラッシュメッセージ用のモック
        $sessionStore->shouldReceive('put')
            ->withAnyArgs()
            ->andReturnNull();

        // リクエストパラメータのモック
        $this->request->shouldReceive('getParameters')
            ->once()
            ->andReturn($parameters);

        // DB操作のモック - 修正：rollBackは呼ばれない
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->never();  // この行を修正

        // ログ出力のモック - errorも呼ばれない
        Log::shouldReceive('error')->never();  // 修正

        // リダイレクト関連のモック
        $redirectResponse = Mockery::mock('Illuminate\Http\RedirectResponse');
        $redirectResponse->shouldReceive('with')
            ->withAnyArgs()
            ->andReturnSelf();

        // routeメソッドのモック
        Redirect::shouldReceive('route')
            ->with('apply.lists.index')
            ->once()
            ->andReturn($redirectResponse);

        // 実行
        $response = $this->controller->__invoke($this->request);

        // アサーション
        $this->assertSame($redirectResponse, $response);
    }

    public function test_申出作成時にエラーが発生した場合は元の画面に戻されること()
    {
        // テストデータ
        $parameters = [
            'apply_type_id' => 1,
            'skip_url_id' => 'test-skip-url'
        ];
        $skipUrlUlid = 'test-ulid';
        $exception = new \Exception('テストエラー');

        // セッションストアのモック
        $sessionStore = $this->app['session.store'];
        $sessionStore->shouldReceive('get')
            ->with('skip_preliminary_ulid', Mockery::any())
            ->andReturn($skipUrlUlid);

        // フラッシュメッセージ用のモック
        $sessionStore->shouldReceive('put')
            ->withAnyArgs()
            ->andReturnNull();

        // リクエストパラメータのモック
        $this->request->shouldReceive('getParameters')
            ->once()
            ->andReturn($parameters);

        // サービスのモック
        $this->applyCreateService->shouldReceive('createApplyFromMinimumInfo')
            ->with($parameters, $skipUrlUlid)
            ->once()
            ->andThrow($exception);

        // DB操作のモック
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        // ログ出力のモック
        Log::shouldReceive('error')
            ->withAnyArgs()
            ->once();

        // リダイレクト関連のモック
        $redirectResponse = Mockery::mock('Illuminate\Http\RedirectResponse');
        $redirectResponse->shouldReceive('with')
            ->withAnyArgs()
            ->andReturnSelf();

        $redirectResponse->shouldReceive('withInput')
            ->andReturnSelf();

        // backメソッドのモック
        Redirect::shouldReceive('back')
            ->andReturn($redirectResponse);

        // 実行
        $response = $this->controller->__invoke($this->request);

        // アサーション
        $this->assertSame($redirectResponse, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
