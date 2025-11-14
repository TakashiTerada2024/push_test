<?php

namespace Ncc01\Apply\Application\Usecase;

use Ncc01\Apply\Application\InputBoundary\SaveScreenLocksParameterInterface;

interface SaveScreenLocksInterface
{
    /**
     * 画面ロック情報を保存
     *
     * @param array $screenLocks ロック情報の配列
     * @param int $applyId 申請ID
     * @return void
     */
    public function __invoke(int $applyId, SaveScreenLocksParameterInterface $parameter): void;
}
