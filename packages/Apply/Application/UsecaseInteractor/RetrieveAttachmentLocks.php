<?php

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\AttachmentLockRepositoryInterface;
use Ncc01\Apply\Application\Usecase\RetrieveAttachmentLocksInterface;

/**
 * RetrieveAttachmentLocks
 */
class RetrieveAttachmentLocks implements RetrieveAttachmentLocksInterface
{
    /** @var AttachmentLockRepositoryInterface */
    private $attachmentLockRepository;

    /**
     * @param AttachmentLockRepositoryInterface $attachmentLockRepository
     */
    public function __construct(AttachmentLockRepositoryInterface $attachmentLockRepository)
    {
        $this->attachmentLockRepository = $attachmentLockRepository;
    }

    /**
     * @param int $applyId
     * @return array
     */
    public function __invoke(int $applyId): array
    {
        return $this->attachmentLockRepository->findByApplyId($applyId);
    }
}
