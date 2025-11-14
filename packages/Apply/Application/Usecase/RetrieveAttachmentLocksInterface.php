<?php

namespace Ncc01\Apply\Application\Usecase;

/**
 * RetrieveAttachmentLocksInterface
 */
interface RetrieveAttachmentLocksInterface
{
    /**
     * @param int $applyId
     * @return array
     */
    public function __invoke(int $applyId): array;
}
