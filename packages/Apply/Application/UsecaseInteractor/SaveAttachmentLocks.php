<?php

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\Gateway\AttachmentLockRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveAttachmentLocksParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveAttachmentLocksInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use RuntimeException;

class SaveAttachmentLocks implements SaveAttachmentLocksInterface
{
    /**
     * @param ApplyRepositoryInterface $applyRepository
     * @param AttachmentLockRepositoryInterface $attachmentLockRepository
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private AttachmentLockRepositoryInterface $attachmentLockRepository,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $applyId, SaveAttachmentLocksParameterInterface $parameter): void
    {
        // 申請の存在確認
        $this->applyRepository->findById($applyId);

        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        $this->attachmentLockRepository->save(
            $applyId,
            $parameter->getAttachmentLocks(),
            $authenticatedUser->getId()
        );
    }
}
