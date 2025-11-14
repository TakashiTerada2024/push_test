<?php

/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Http\Controllers\Apply;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\GenerateSkipUrlRequest;
use App\Services\ApplicationSkipUrlService;
use Illuminate\Http\JsonResponse;

/**
 * GenerateSkipUrlController
 * 申出事前相談スキップURL生成コントローラ
 *
 * @package App\Http\Controllers\Apply
 */
class GenerateSkipUrlController extends Controller
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
    public function __invoke(GenerateSkipUrlRequest $request): JsonResponse
    {
        try {
            // スキップURLを生成
            $result = $this->skipUrlService->generateUrl(
                $request->getApplyTypeId(),
                $request->getCreatedBy()
            );

            // フロントエンド側で表示・コピーするためのURLを生成
            $baseUrl = config('app.url');
            $skipUrlPath = "/apply/skip/{$result['ulid']}";
            $fullUrl = rtrim($baseUrl, '/') . $skipUrlPath;

            // 有効期限の表示用フォーマット
            $expiredAt = $result['expired_at'];
            $expiredAtFormatted = $expiredAt ? $expiredAt->format('Y年m月d日 H:i') : null;

            // フロントエンド用のメッセージを構築
            $message = "以下のURLから申出の新規作成ができます。このURLは第三者に知られないよう取り扱いにご注意ください。\n\n";
            $message .= "申出種別：{$request->getApplyTypeName()}\n";
            $message .= "URL：{$fullUrl}\n";
            $message .= "有効期限：{$expiredAtFormatted}まで（14日間）";

            // レスポンスとして返却
            return response()->json([
                'success' => true,
                'message' => 'スキップURLを生成しました',
                'data' => [
                    'url' => $fullUrl,
                    'ulid' => $result['ulid'],
                    'apply_type_id' => $result['apply_type_id'],
                    'apply_type_name' => $request->getApplyTypeName(),
                    'expired_at' => $expiredAtFormatted,
                    'text_to_copy' => $message
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'スキップURLの生成に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }
}
