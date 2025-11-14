<?php

namespace Ncc01\Apply\Application\InputBoundary;

interface SaveAttachmentLocksParameterInterface
{
    /**
     * ロック情報の配列を取得
     *
     * @return array
     */
    public function getAttachmentLocks(): array;
}
