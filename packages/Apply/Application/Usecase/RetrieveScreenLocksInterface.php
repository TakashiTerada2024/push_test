<?php

namespace Ncc01\Apply\Application\Usecase;

/**
 * RetrieveScreenLocksInterface
 */
interface RetrieveScreenLocksInterface
{
    /**
     * @param int $applyId
     * @return array
     */
    public function __invoke(int $applyId): array;
}
