<?php

namespace Ncc01\Apply\Application\InputBoundary;

interface SaveScreenLocksParameterInterface
{
    /**
     * ロック情報の配列を取得
     *
     * @return array
     */
    public function getScreenLocks(): array;
}
