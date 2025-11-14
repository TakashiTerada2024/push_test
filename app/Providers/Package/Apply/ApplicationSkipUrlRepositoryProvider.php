<?php

namespace App\Providers\Package\Apply;

use Illuminate\Support\ServiceProvider;

class ApplicationSkipUrlRepositoryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Ncc01\Apply\Enterprise\Gateway\ApplicationSkipUrlRepositoryInterface::class,
            \App\Gateway\Repository\Apply\ApplicationSkipUrlRepository::class
        );
    }
}
