<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Log;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * アカウント登録後のレスポンスを生成
     * セッションに保存されたURL（intended）へリダイレクトする
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $intended = redirect()->intended(config('fortify.home'));

        Log::info('User registered, redirecting to intended URL', [
            'user_id' => auth()->id(),
            'url' => $intended->getTargetUrl()
        ]);

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : $intended;
    }
}
