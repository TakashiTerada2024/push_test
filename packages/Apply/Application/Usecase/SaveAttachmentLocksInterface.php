<?php

namespace Ncc01\Apply\Application\Usecase;

use Ncc01\Apply\Application\InputBoundary\SaveAttachmentLocksParameterInterface;

interface SaveAttachmentLocksInterface
{
    /**
     * 添付資料ロック情報を保存
     *
     * @param int $applyId 申請ID
     * @param SaveAttachmentLocksParameterInterface $parameter
     * @return void
     */
    public function __invoke(int $applyId, SaveAttachmentLocksParameterInterface $parameter): void;
}
