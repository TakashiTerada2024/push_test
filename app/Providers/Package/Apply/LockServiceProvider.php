<?php

namespace App\Providers\Package\Apply;

use Illuminate\Support\ServiceProvider;
use Ncc01\Apply\Application\UsecaseInteractor\RetrieveAttachmentLocks;
use Ncc01\Apply\Application\Usecase\RetrieveAttachmentLocksInterface;
use Ncc01\Apply\Application\UsecaseInteractor\RetrieveScreenLocks;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * LockServiceProvider
 */
class LockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RetrieveAttachmentLocksInterface::class, RetrieveAttachmentLocks::class);
        $this->app->bind(RetrieveScreenLocksInterface::class, RetrieveScreenLocks::class);
    }
}
