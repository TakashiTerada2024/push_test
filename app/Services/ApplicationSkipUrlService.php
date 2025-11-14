<?php

namespace App\Services;

use Carbon\Carbon;
use Ncc01\Apply\Application\Service\ApplicationSkipUrlService as DomainApplicationSkipUrlService;

/**
 * 申出スキップURL生成サービス
 * アプリケーション層のサービス
 */
class ApplicationSkipUrlService
{
    /**
     * @var DomainApplicationSkipUrlService
     */
    private $domainService;

    /**
     * コンストラクタ
     *
     * @param DomainApplicationSkipUrlService $domainService
     */
    public function __construct(DomainApplicationSkipUrlService $domainService)
    {
        $this->domainService = $domainService;
    }

    /**
     * 申出スキップURLを生成する
     *
     * @param int $applyTypeId 申出種別ID
     * @param int $userId 作成者ID
     * @param int|null $expiresInDays 有効期限（日数）
     * @return array 生成されたURLの情報
     */
    public function generateUrl(int $applyTypeId, int $userId, ?int $expiresInDays = 14): array
    {
        // ドメインサービスを使用してスキップURLを生成
        $skipUrl = $this->domainService->generate($applyTypeId, $userId, $expiresInDays);

        // アプリケーション用のレスポンス形式に変換
        return [
            'ulid' => $skipUrl->getUlid(),
            'apply_type_id' => $skipUrl->getApplyTypeId(),
            'expired_at' => $skipUrl->getExpiredAt()
        ];
    }

    /**
     * ULIDからスキップURLを検証する
     *
     * @param string $ulid
     * @return array|null 有効なスキップURLの情報、無効な場合はnull
     */
    public function validate(string $ulid): ?array
    {
        try {
            $skipUrl = $this->domainService->validateByUlid($ulid);

            return [
                'ulid' => $skipUrl->getUlid(),
                'apply_type_id' => $skipUrl->getApplyTypeId(),
                'created_by' => $skipUrl->getCreatedBy(),
                'expired_at' => $skipUrl->getExpiredAt()
            ];
        } catch (\Exception $e) {
            // 例外をキャッチしてnullを返す
            return null;
        }
    }

    /**
     * スキップURLを使用済みにする
     *
     * @param string $ulid
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     */
    public function markAsUsed(string $ulid): bool
    {
        try {
            $this->domainService->markAsUsed($ulid);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
