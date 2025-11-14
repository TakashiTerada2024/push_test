<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * 事前相談スキップトークンの存在を確認するミドルウェア
 */
class CheckSkipPreliminaryToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // POSTリクエストの場合は、セッションチェックをスキップ
        if ($request->isMethod('post')) {
            // POSTリクエストの場合、ログイン状態のみ確認
            if (!Auth::check()) {
                Log::warning('User not authenticated for skip preliminary flow');
                return redirect()->route('login')
                    ->with('message', '申出文書作成を開始するにはログインが必要です。');
            }

            return $next($request);
        }

        // GETリクエストの場合は、これまで通りセッションチェックを行う
        // セッションにトークン情報が存在するか確認
        if (!session()->has('skip_preliminary_ulid') || !session()->has('skip_preliminary_data')) {
            Log::warning('Skip preliminary token session not found');
            // トークン情報がない場合は通常のフローへ
            return redirect()->route('welcome')
                ->with('error', '事前相談スキップ用のURLからアクセスしてください。');
        }

        // ログイン状態を確認
        if (!Auth::check()) {
            Log::warning('User not authenticated for skip preliminary flow');
            return redirect()->route('login')
                ->with('message', '申出文書作成を開始するにはログインが必要です。');
        }

        return $next($request);
    }
}
