<?php

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ScreenLockRepositoryInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * RetrieveScreenLocks
 */
class RetrieveScreenLocks implements RetrieveScreenLocksInterface
{
    /** @var ScreenLockRepositoryInterface */
    private $screenLockRepository;

    /**
     * @param ScreenLockRepositoryInterface $screenLockRepository
     */
    public function __construct(ScreenLockRepositoryInterface $screenLockRepository)
    {
        $this->screenLockRepository = $screenLockRepository;
    }

    /**
     * @param int $applyId
     * @return array
     */
    public function __invoke(int $applyId): array
    {
        return $this->screenLockRepository->findByApplyId($applyId);
    }
}
