<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment(['production'])) {
            URL::forceScheme('https');
        }

        // コンポーネントの登録
        Blade::component('generate-skip-url-modal', \App\View\Components\GenerateSkipUrlModal::class);
    }
}
