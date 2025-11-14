<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app-ncc01.system.organization') }}　{{ config('app-ncc01.system.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <div id="container">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow {{ $headerclass ?? '' }}">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <x-jet-banner />
                <main>
                    @if ($errors->any())
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center">
                                <svg class="h-5 w-5 text-red-700 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                入力エラー：赤字で表示されている入力項目の内容をご確認ください。
                            </div>
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('scripts')

    </body>
</html>
