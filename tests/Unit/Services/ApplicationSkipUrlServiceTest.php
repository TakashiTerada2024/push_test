<?php

namespace Tests\Unit\Services;

use App\Services\ApplicationSkipUrlService;
use Carbon\Carbon;
use Mockery;
use Mockery\MockInterface;
use Ncc01\Apply\Application\Service\ApplicationSkipUrlService as DomainApplicationSkipUrlService;
use Ncc01\Apply\Enterprise\Entity\ApplicationSkipUrl;
use Tests\TestCase;

class ApplicationSkipUrlServiceTest extends TestCase
{
    /**
     * @var MockInterface
     */
    private $domainService;

    /**
     * @var ApplicationSkipUrlService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        // ドメインサービスのモックを作成
        $this->domainService = Mockery::mock(DomainApplicationSkipUrlService::class);

        // テスト対象のサービスをインスタンス化
        $this->service = new ApplicationSkipUrlService($this->domainService);
    }

    /**
     * @test
     */
    public function test申出スキップURLを正常に生成できること()
    {
        // テストデータ
        $applyTypeId = 1;
        $userId = 100;
        $expiresInDays = 7;
        $expectedUlid = '01FGABCDEFGHIJKLMNOPQRSTU';
        $expectedExpiredAt = Carbon::now()->addDays($expiresInDays);

        // モックの戻り値を設定
        $skipUrlMock = Mockery::mock(ApplicationSkipUrl::class);
        $skipUrlMock->shouldReceive('getUlid')->andReturn($expectedUlid);
        $skipUrlMock->shouldReceive('getApplyTypeId')->andReturn($applyTypeId);
        $skipUrlMock->shouldReceive('getExpiredAt')->andReturn($expectedExpiredAt);

        $this->domainService->shouldReceive('generate')
            ->with($applyTypeId, $userId, $expiresInDays)
            ->once()
            ->andReturn($skipUrlMock);

        // 実行
        $result = $this->service->generateUrl($applyTypeId, $userId, $expiresInDays);

        // 検証
        $this->assertEquals([
            'ulid' => $expectedUlid,
            'apply_type_id' => $applyTypeId,
            'expired_at' => $expectedExpiredAt
        ], $result);
    }

    /**
     * @test
     */
    public function test申出スキップURLをデフォルト有効期限で生成できること()
    {
        // テストデータ
        $applyTypeId = 2;
        $userId = 200;
        $defaultExpiresInDays = 14; // デフォルト値
        $expectedUlid = '01FGABCDEFGHIJKLMNOPQRSTY';
        $expectedExpiredAt = Carbon::now()->addDays($defaultExpiresInDays);

        // モックの戻り値を設定
        $skipUrlMock = Mockery::mock(ApplicationSkipUrl::class);
        $skipUrlMock->shouldReceive('getUlid')->andReturn($expectedUlid);
        $skipUrlMock->shouldReceive('getApplyTypeId')->andReturn($applyTypeId);
        $skipUrlMock->shouldReceive('getExpiredAt')->andReturn($expectedExpiredAt);

        $this->domainService->shouldReceive('generate')
            ->with($applyTypeId, $userId, $defaultExpiresInDays)
            ->once()
            ->andReturn($skipUrlMock);

        // 実行（有効期限を省略）
        $result = $this->service->generateUrl($applyTypeId, $userId);

        // 検証
        $this->assertEquals([
            'ulid' => $expectedUlid,
            'apply_type_id' => $applyTypeId,
            'expired_at' => $expectedExpiredAt
        ], $result);
    }

    /**
     * @test
     */
    public function test有効な申出スキップURLを検証できること()
    {
        // テストデータ
        $ulid = '01FGABCDEFGHIJKLMNOPQRSTV';
        $applyTypeId = 3;
        $createdBy = 300;
        $expiredAt = Carbon::now()->addDays(7);

        // モックの戻り値を設定
        $skipUrlMock = Mockery::mock(ApplicationSkipUrl::class);
        $skipUrlMock->shouldReceive('getUlid')->andReturn($ulid);
        $skipUrlMock->shouldReceive('getApplyTypeId')->andReturn($applyTypeId);
        $skipUrlMock->shouldReceive('getCreatedBy')->andReturn($createdBy);
        $skipUrlMock->shouldReceive('getExpiredAt')->andReturn($expiredAt);

        $this->domainService->shouldReceive('validateByUlid')
            ->with($ulid)
            ->once()
            ->andReturn($skipUrlMock);

        // 実行
        $result = $this->service->validate($ulid);

        // 検証
        $this->assertEquals([
            'ulid' => $ulid,
            'apply_type_id' => $applyTypeId,
            'created_by' => $createdBy,
            'expired_at' => $expiredAt
        ], $result);
    }

    /**
     * @test
     */
    public function test無効な申出スキップURLを検証するとnullが返ること()
    {
        // テストデータ
        $ulid = 'INVALID_ULID';

        // モックの戻り値を設定
        $this->domainService->shouldReceive('validateByUlid')
            ->with($ulid)
            ->once()
            ->andThrow(new \Exception('Invalid ULID'));

        // 実行
        $result = $this->service->validate($ulid);

        // 検証
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function test申出スキップURLを使用済みにできること()
    {
        // テストデータ
        $ulid = '01FGABCDEFGHIJKLMNOPQRSTW';

        // モックの期待値を設定
        $this->domainService->shouldReceive('markAsUsed')
            ->with($ulid)
            ->once();

        // 実行
        $result = $this->service->markAsUsed($ulid);

        // 検証
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function test無効な申出スキップURLを使用済みにするとfalseが返ること()
    {
        // テストデータ
        $ulid = 'INVALID_ULID';

        // モックの期待値を設定
        $this->domainService->shouldReceive('markAsUsed')
            ->with($ulid)
            ->once()
            ->andThrow(new \Exception('Invalid ULID'));

        // 実行
        $result = $this->service->markAsUsed($ulid);

        // 検証
        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
