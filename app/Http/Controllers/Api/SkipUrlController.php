<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateSkipUrlRequest;
use App\Services\ApplicationSkipUrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SkipUrlController extends Controller
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
     * 申出スキップURLを生成する
     *
     * @param GenerateSkipUrlRequest $request
     * @return JsonResponse
     */
    public function generateUrl(GenerateSkipUrlRequest $request): JsonResponse
    {
        try {
            // 認証チェック
            $userId = auth()->id();
            if ($userId === null) {
                Log::error('認証ユーザーのIDがnullです。auth()->user() = ' . json_encode(auth()->user()));
                return response()->json([
                    'success' => false,
                    'message' => '認証ユーザーIDの取得に失敗しました。'
                ], 500);
            }

            // サービスにビジネスロジックを委譲
            $result = $this->skipUrlService->generateUrl(
                $request->input('apply_type_id'),
                $userId
            );

            // 成功レスポンスを返却
            return response()->json([
                'success' => true,
                'ulid' => $result['ulid'],
                'apply_type_id' => $result['apply_type_id'],
                'expired_at' => $result['expired_at']->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('スキップURL生成エラー: ' . $e->getMessage(), [
                'apply_type_id' => $request->input('apply_type_id'),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
