<?php

namespace Tests\Unit\Http\Controllers\Apply;

use App\Http\Controllers\Apply\GenerateSkipUrlController;
use App\Http\Requests\Apply\GenerateSkipUrlRequest;
use App\Services\ApplicationSkipUrlService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GenerateSkipUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    private $skipUrlService;
    private $controller;
    private $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->skipUrlService = Mockery::mock(ApplicationSkipUrlService::class);
        $this->controller = new GenerateSkipUrlController($this->skipUrlService);
        $this->baseUrl = 'http://localhost';
        config(['app.url' => $this->baseUrl]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_スキップURLが正しく生成されること()
    {
        // テストデータ
        $applyTypeId = 1;
        $createdBy = 100;
        $applyTypeName = 'テスト申請種別';
        $ulid = 'TEST_ULID_12345';
        $expiredAt = Carbon::now()->addDays(14);
        $expiredAtFormatted = $expiredAt->format('Y年m月d日 H:i');

        // リクエストのモック作成
        $request = Mockery::mock(GenerateSkipUrlRequest::class);
        $request->shouldReceive('getApplyTypeId')->andReturn($applyTypeId);
        $request->shouldReceive('getCreatedBy')->andReturn($createdBy);
        $request->shouldReceive('getApplyTypeName')->andReturn($applyTypeName);

        // サービスの戻り値を設定
        $this->skipUrlService->shouldReceive('generateUrl')
            ->once()
            ->with($applyTypeId, $createdBy)
            ->andReturn([
                'ulid' => $ulid,
                'apply_type_id' => $applyTypeId,
                'expired_at' => $expiredAt
            ]);

        // コントローラーの実行
        $response = $this->controller->__invoke($request);

        // レスポンスの検証
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('スキップURLを生成しました', $responseData['message']);

        $data = $responseData['data'];
        $expectedUrl = "{$this->baseUrl}/apply/skip/{$ulid}";
        $this->assertEquals($expectedUrl, $data['url']);
        $this->assertEquals($ulid, $data['ulid']);
        $this->assertEquals($applyTypeId, $data['apply_type_id']);
        $this->assertEquals($applyTypeName, $data['apply_type_name']);
        $this->assertEquals($expiredAtFormatted, $data['expired_at']);

        // コピー用テキストの検証
        $expectedMessage = "以下のURLから申出の新規作成ができます。このURLは第三者に知られないよう取り扱いにご注意ください。\n\n";
        $expectedMessage .= "申出種別：{$applyTypeName}\n";
        $expectedMessage .= "URL：{$expectedUrl}\n";
        $expectedMessage .= "有効期限：{$expiredAtFormatted}まで（14日間）";
        $this->assertEquals($expectedMessage, $data['text_to_copy']);
    }

    public function test_サービスで例外が発生した場合にエラーレスポンスが返却されること()
    {
        // テストデータ
        $applyTypeId = 1;
        $createdBy = 100;
        $errorMessage = 'テストエラー';

        // リクエストのモック作成
        $request = Mockery::mock(GenerateSkipUrlRequest::class);
        $request->shouldReceive('getApplyTypeId')->andReturn($applyTypeId);
        $request->shouldReceive('getCreatedBy')->andReturn($createdBy);

        // サービスで例外が発生するように設定
        $this->skipUrlService->shouldReceive('generateUrl')
            ->once()
            ->with($applyTypeId, $createdBy)
            ->andThrow(new \Exception($errorMessage));

        // コントローラーの実行
        $response = $this->controller->__invoke($request);

        // レスポンスの検証
        $this->assertEquals(500, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals(
            'スキップURLの生成に失敗しました: ' . $errorMessage,
            $responseData['message']
        );
    }
}
