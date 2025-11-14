<?php

namespace App\Providers\Package\Apply;

use Illuminate\Support\ServiceProvider;

class SaveScreenLocksProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Ncc01\Apply\Application\Usecase\SaveScreenLocksInterface::class,
            \Ncc01\Apply\Application\UsecaseInteractor\SaveScreenLocks::class
        );
        $this->app->bind(
            \Ncc01\Apply\Application\InputBoundary\SaveScreenLocksParameterInterface::class,
            \Ncc01\Apply\Application\InputData\SaveScreenLocksParameter::class
        );
        $this->app->bind(
            \Ncc01\Apply\Application\Gateway\ScreenLockRepositoryInterface::class,
            \App\Gateway\Repository\Apply\ScreenLockRepository::class
        );
    }
}
