<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Http\Controllers\Api\SkipUrlController;
use App\Http\Requests\GenerateSkipUrlRequest;
use App\Services\ApplicationSkipUrlService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class SkipUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    private $skipUrlService;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->skipUrlService = Mockery::mock(ApplicationSkipUrlService::class);
        $this->controller = new SkipUrlController($this->skipUrlService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_認証済みユーザーが正しくスキップURLを生成できること()
    {
        // テストデータ
        $userId = 1;
        $applyTypeId = 2;
        $ulid = 'TEST_ULID_12345';
        $expiredAt = Carbon::now()->addDays(14);

        // 認証ユーザーのモック
        $this->actingAs($this->createTestUser($userId));

        // サービスの戻り値を設定
        $this->skipUrlService->shouldReceive('generateUrl')
            ->once()
            ->with($applyTypeId, $userId)
            ->andReturn([
                'ulid' => $ulid,
                'apply_type_id' => $applyTypeId,
                'expired_at' => $expiredAt
            ]);

        // リクエストの作成
        $request = new GenerateSkipUrlRequest();
        $request->merge(['apply_type_id' => $applyTypeId]);

        // コントローラーの実行
        $response = $this->controller->generateUrl($request);

        // レスポンスの検証
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($ulid, $responseData['ulid']);
        $this->assertEquals($applyTypeId, $responseData['apply_type_id']);
        $this->assertEquals($expiredAt->toDateTimeString(), $responseData['expired_at']);
    }

    public function test_未認証ユーザーからのリクエストでエラーが返却されること()
    {
        // リクエストの作成
        $request = new GenerateSkipUrlRequest();
        $request->merge(['apply_type_id' => 1]);

        // コントローラーの実行
        $response = $this->controller->generateUrl($request);

        // レスポンスの検証
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals('認証ユーザーIDの取得に失敗しました。', $responseData['message']);
    }

    public function test_サービス処理で例外が発生した場合にエラーレスポンスが返却されること()
    {
        // テストデータ
        $userId = 1;
        $applyTypeId = 2;
        $errorMessage = 'テストエラー';

        // 認証ユーザーのモック
        $this->actingAs($this->createTestUser($userId));

        // サービスで例外が発生するように設定
        $this->skipUrlService->shouldReceive('generateUrl')
            ->once()
            ->with($applyTypeId, $userId)
            ->andThrow(new \Exception($errorMessage));

        // リクエストの作成
        $request = new GenerateSkipUrlRequest();
        $request->merge(['apply_type_id' => $applyTypeId]);

        // コントローラーの実行
        $response = $this->controller->generateUrl($request);

        // レスポンスの検証
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals($errorMessage, $responseData['message']);
    }

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
