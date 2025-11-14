<?php

namespace App\Providers\Package\Apply;

use Illuminate\Support\ServiceProvider;
use Ncc01\Apply\Application\Service\ApplicationSkipUrlService;

class ApplicationSkipUrlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApplicationSkipUrlService::class, function ($app) {
            return new ApplicationSkipUrlService(
                $app->make(\Ncc01\Apply\Enterprise\Gateway\ApplicationSkipUrlRepositoryInterface::class),
                $app->make(\Ncc01\Common\Application\GatewayInterface\UuidCreatorInterface::class)
            );
        });
    }
}
