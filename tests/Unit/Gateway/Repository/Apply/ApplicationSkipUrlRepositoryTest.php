<?php

namespace Tests\Unit\Gateway\Repository\Apply;

use App\Gateway\Repository\Apply\ApplicationSkipUrlRepository;
use App\Models\ApplicationSkipUrl as ApplicationSkipUrlModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationSkipUrlRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationSkipUrlRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ApplicationSkipUrlRepository();
    }

    public function test_スキップURLエンティティを保存して取得できること()
    {
        // テストデータ作成
        $applyTypeId = 1;
        $userId = 1;
        $expiresInDays = 14;

        // エンティティを作成して保存
        $entity = $this->repository->create($applyTypeId, $userId, $expiresInDays);

        // IDによる取得テスト
        $foundById = $this->repository->findById($entity->getId());
        $this->assertNotNull($foundById);
        $this->assertEquals($entity->getId(), $foundById->getId());
        $this->assertEquals($entity->getUlid(), $foundById->getUlid());

        // ULIDによる取得テスト
        $foundByUlid = $this->repository->findByUlid($entity->getUlid());
        $this->assertNotNull($foundByUlid);
        $this->assertEquals($entity->getId(), $foundByUlid->getId());
    }

    public function test_存在しないIDで検索するとnullが返却されること()
    {
        $this->assertNull($this->repository->findById(99999));
        $this->assertNull($this->repository->findByUlid('NONEXISTENT_ULID'));
    }

    public function test_スキップURLを使用済みにできること()
    {
        // テストデータ作成
        $entity = $this->repository->create(1, 1, 14);

        // 使用済みにする
        $usedEntity = $this->repository->markAsUsed($entity);

        $this->assertTrue($usedEntity->isUsed());

        // DBからも取得して確認
        $foundEntity = $this->repository->findById($entity->getId());
        $this->assertTrue($foundEntity->isUsed());
    }

    public function test_新規スキップURLを作成できること()
    {
        $applyTypeId = 1;
        $userId = 1;
        $expiresInDays = 14;

        $entity = $this->repository->create($applyTypeId, $userId, $expiresInDays);

        $this->assertNotNull($entity);
        $this->assertEquals($applyTypeId, $entity->getApplyTypeId());
        $this->assertEquals($userId, $entity->getCreatedBy());
        $this->assertFalse($entity->isUsed());

        // ULIDフォーマットの検証
        $this->assertEquals(26, strlen($entity->getUlid()));

        // 有効期限の検証
        $expectedExpiredAt = Carbon::now()->addDays($expiresInDays)->startOfDay();
        $this->assertEquals(
            $expectedExpiredAt->format('Y-m-d'),
            $entity->getExpiredAt()->format('Y-m-d')
        );
    }

    public function test_有効なスキップURLを取得できること()
    {
        // 有効なURLを作成
        $entity = $this->repository->create(1, 1, 14);

        // 有効なURLを取得
        $validEntity = $this->repository->findValidByUlid($entity->getUlid());

        $this->assertNotNull($validEntity);
        $this->assertEquals($entity->getId(), $validEntity->getId());
    }

    public function test_使用済みまたは期限切れのスキップURLはnullが返却されること()
    {
        // 使用済みURLのテスト
        $usedEntity = $this->repository->create(1, 1, 14);
        $this->repository->markAsUsed($usedEntity);
        $this->assertNull($this->repository->findValidByUlid($usedEntity->getUlid()));

        // 期限切れURLのテスト
        $expiredEntity = $this->repository->create(1, 1, -1); // 昨日で期限切れ
        $this->assertNull($this->repository->findValidByUlid($expiredEntity->getUlid()));
    }
}
