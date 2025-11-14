<?php

namespace App\Services\Apply;

use App\Services\ApplicationSkipUrlService;
use Illuminate\Support\Facades\Log;

/**
 * 事前相談スキップ処理サービス
 */
class SkipPreliminaryService
{
    /**
     * @var ApplicationSkipUrlService
     */
    private $skipUrlService;

    /**
     * コンストラクタ
     *
     * @param ApplicationSkipUrlService $skipUrlService
     */
    public function __construct(ApplicationSkipUrlService $skipUrlService)
    {
        $this->skipUrlService = $skipUrlService;
    }

    /**
     * ULIDを検証し、有効なトークンデータを取得する
     *
     * @param string $ulid
     * @return array|null 有効なトークンの場合はトークンデータ、無効な場合はnull
     */
    public function validateToken(string $ulid): ?array
    {
        try {
            // スキップURLサービスを使用して検証
            $result = $this->skipUrlService->validate($ulid);

            if (!$result) {
                Log::info('Invalid or expired skip preliminary token accessed: ' . $ulid);
                return null;
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error validating skip preliminary token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * スキップURLを使用済みにする
     *
     * @param string $ulid
     * @return bool 成功した場合はtrue、失敗した場合はfalse
     * @SuppressWarnings(PHPMD.ElseExpression) 簡潔な内容なので問題なし
     */
    public function markTokenAsUsed(string $ulid): bool
    {
        try {
            $result = $this->skipUrlService->markAsUsed($ulid);

            if ($result) {
                Log::info('Skip URL marked as used successfully: ' . $ulid);
            } else {
                Log::warning('Failed to mark skip URL as used: ' . $ulid);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error marking skip URL as used: ' . $e->getMessage());
            return false;
        }
    }
}
