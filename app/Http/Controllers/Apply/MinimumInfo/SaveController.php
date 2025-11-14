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

namespace App\Http\Controllers\Apply\MinimumInfo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\MinimumInfoRequest;
use App\Models\ApplicationSkipUrl;
use App\Services\Apply\MinimumInfoApplyCreateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

/**
 * 最低限必要な情報保存処理コントローラー
 */
class SaveController extends Controller
{
    /**
     * @var MinimumInfoApplyCreateService
     */
    protected $applyCreateService;

    /**
     * コンストラクタ
     *
     * @param MinimumInfoApplyCreateService $applyCreateService
     */
    public function __construct(MinimumInfoApplyCreateService $applyCreateService)
    {
        $this->applyCreateService = $applyCreateService;
    }

    /**
     * 最低限必要な情報を保存し、申出を作成する
     *
     * @param MinimumInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(MinimumInfoRequest $request)
    {
        try {
            DB::beginTransaction();

            // 申出を作成
            $parameters = $request->getParameters();
            $skipUrlUlid = session('skip_preliminary_ulid');
            if (empty($skipUrlUlid)) {
                return redirect()->route('apply.lists.index')
                    ->with('error', 'スキップURLの情報が見つかりません。申出の新規作成からやり直してください。');
            }

            $applyId = $this->applyCreateService->createApplyFromMinimumInfo($parameters, $skipUrlUlid);

            // セッションからスキップ情報を削除
            if ($parameters['skip_url_id']) {
                session()->forget(['skip_preliminary_ulid', 'skip_preliminary_data']);
            }

            DB::commit();

            // ログ出力
            Log::info('Created apply from minimum info', [
                'user_id' => auth()->id(),
                'apply_id' => $applyId,
                'apply_type' => $parameters['apply_type_id'],
            ]);

            // 申出詳細画面へリダイレクト
            return redirect()->route('apply.detail.overview', ['applyId' => $applyId])
                ->with('success', '最低限必要な情報を登録し、申出を作成しました。詳細情報を入力してください。');
        } catch (\Exception $e) {
            DB::rollBack();

            // エラーログ
            Log::error('Failed to create apply from minimum info', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->with('error', '申出の作成に失敗しました。もう一度お試しください。');
        }
    }
}
