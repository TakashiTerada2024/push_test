<?php

namespace Ncc01\Apply\Application\InputData;

use Ncc01\Apply\Application\InputBoundary\SaveAttachmentLocksParameterInterface;

class SaveAttachmentLocksParameter implements SaveAttachmentLocksParameterInterface
{
    /**
     * @param array $attachmentLocks
     */
    public function __construct(
        private array $attachmentLocks
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachmentLocks(): array
    {
        return $this->attachmentLocks;
    }
}
