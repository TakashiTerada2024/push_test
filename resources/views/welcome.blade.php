<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app-ncc01.system.organization') }}　{{ config('app-ncc01.system.name') }}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-main-500 items-center py-4">
            @if (Route::has('login'))
                <div class="px-6 py-4 sm:block">
                    <h1 class="text-white text-2xl font-bold text-center pb-4 mb-6">{{ config('app-ncc01.system.organization') }} <span class="display-inlineblock">{{ config('app-ncc01.system.name') }}</span></h1>
                    <div class="text-center top-page">
                    @auth
                        <a href="{{ url('/welcome') }}" class="bg-white font-bold text-gray-700 hover:bg-sub-500 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            ダッシュボード
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white font-bold text-gray-700 hover:bg-sub-500 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            ログイン
                        </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-white font-bold text-gray-700 hover:bg-sub-500 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            新規登録
                        </a>
                    @endif
                    @endauth
                    </div>

                    <div class="mt-8">
                        <div style="padding: 1.5rem 3rem; border: 1px solid #9ca3af; border-radius: 0.5rem; background-color: #1f2937; color: white;">
                            <h2 class="text-xl font-bold mb-4">メンテナンスのお知らせ</h2>
                            <p class="mb-4">平素より当システムをご利用いただき、誠にありがとうございます。<br>
                            下記の日時におきまして、システムのメンテナンス作業を実施いたします。</p>

                            <h3 class="font-bold mb-2">【メンテナンス日時】</h3>
                            <p class="mb-4">2025年5月27日（火） 12:00 ～ 13:00（予定）</p>
                            <p class="mb-4">2025年5月29日（木） 12:00 ～ 13:00（予定）</p>

                            <h3 class="font-bold mb-2">【影響範囲】</h3>
                            <p class="mb-4">メンテナンス作業中は、当システムをご利用いただくことができません。</p>

                            <h3 class="font-bold mb-2">【ご注意】</h3>
                            <p>作業状況により、終了時刻が前後する場合がございます。<br>
                            ご利用の皆様にはご不便をおかけいたしますが、ご理解とご協力を賜りますようお願い申し上げます。</p>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </body>
</html>
