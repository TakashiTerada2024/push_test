<?php

namespace App\Providers\Package\Apply;

use Illuminate\Support\ServiceProvider;

class SaveAttachmentLocksProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Ncc01\Apply\Application\Usecase\SaveAttachmentLocksInterface::class,
            \Ncc01\Apply\Application\UsecaseInteractor\SaveAttachmentLocks::class
        );
        $this->app->bind(
            \Ncc01\Apply\Application\InputBoundary\SaveAttachmentLocksParameterInterface::class,
            \Ncc01\Apply\Application\InputData\SaveAttachmentLocksParameter::class
        );
        $this->app->bind(
            \Ncc01\Apply\Application\Gateway\AttachmentLockRepositoryInterface::class,
            \App\Gateway\Repository\Apply\AttachmentLockRepository::class
        );
    }
}
