<?php

namespace Ncc01\Apply\Application\InputData;

use Ncc01\Apply\Application\InputBoundary\SaveScreenLocksParameterInterface;

class SaveScreenLocksParameter implements SaveScreenLocksParameterInterface
{
    /**
     * @param string $applyId
     * @param array $screenLocks
     */
    public function __construct(
        private array $screenLocks
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getScreenLocks(): array
    {
        return $this->screenLocks;
    }
}
