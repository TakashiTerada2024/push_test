<?php

namespace App\Http\Controllers\Apply;

use App\Http\Controllers\Controller;
use App\Services\Apply\SkipPreliminaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * 事前相談スキップURLアクセス処理用コントローラー
 */
class SkipPreliminaryController extends Controller
{
    /**
     * @var SkipPreliminaryService
     */
    protected $skipPreliminaryService;

    /**
     * コンストラクタ
     *
     * @param SkipPreliminaryService $skipPreliminaryService
     */
    public function __construct(SkipPreliminaryService $skipPreliminaryService)
    {
        $this->skipPreliminaryService = $skipPreliminaryService;
    }

    /**
     * 事前相談スキップURLアクセス時の処理
     *
     * @param Request $request
     * @param string $ulid トークン
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, string $ulid)
    {
        Log::info('Skip preliminary URL accessed. ULID: ' . $ulid);

        // ULIDの検証
        $tokenData = $this->skipPreliminaryService->validateToken($ulid);

        if (!$tokenData) {
            return redirect()->route('welcome')
                ->with('error', '無効なURLです。事前相談スキップ用のURLを再度確認してください。');
        }

        // トークン情報をセッションに保存
        session(['skip_preliminary_ulid' => $ulid]);
        session(['skip_preliminary_data' => $tokenData]);

        // ログイン状態チェック
        if (Auth::check()) {
            // 事務局ユーザーの場合、申出検索画面へリダイレクト
            if (Auth::user()->role_id === 2) {
                Log::info('Secretariat user accessed skip preliminary URL, redirecting to apply search page', [
                    'user_id' => Auth::id(),
                    'ulid' => $ulid
                ]);

                return redirect()->route('apply.lists.search')
                    ->with('info', '事務局ユーザーは申出スキップURLを使用できません。');
            }

            // ログイン済みの場合、次のステップ（最低限必要な情報登録画面）へリダイレクト
            Log::info('User is authenticated, redirecting to minimum info page', [
                'user_id' => Auth::id(),
                'ulid' => $ulid
            ]);

            return redirect()->route('apply.minimum-info.create');
        } else {
            // 未ログインの場合、ログイン画面へリダイレクト
            // ログイン後に元の処理に戻れるよう、intended URLを設定
            Log::info('User is not authenticated, redirecting to login page', [
                'ulid' => $ulid
            ]);

            // ログイン後に最低限必要な情報登録画面に遷移するよう設定
            $request->session()->put('url.intended', route('apply.minimum-info.create'));

            return redirect()->route('login');
        }
    }
}
