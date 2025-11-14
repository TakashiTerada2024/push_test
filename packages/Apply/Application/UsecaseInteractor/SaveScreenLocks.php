<?php

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\Gateway\ScreenLockRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveScreenLocksParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveScreenLocksInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use RuntimeException;

class SaveScreenLocks implements SaveScreenLocksInterface
{
    /**
     * @param ApplyRepositoryInterface $applyRepository
     * @param ScreenLockRepositoryInterface $screenLockRepository
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private ScreenLockRepositoryInterface $screenLockRepository,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $applyId, SaveScreenLocksParameterInterface $parameter): void
    {
        // applyの存在確認
        $this->applyRepository->findById($applyId);

        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        $this->screenLockRepository->save(
            $applyId,
            $parameter->getScreenLocks(),
            $authenticatedUser->getId()
        );
    }
}
