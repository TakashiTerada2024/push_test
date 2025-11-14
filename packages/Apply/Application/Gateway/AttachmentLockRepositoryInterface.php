<?php

namespace Ncc01\Apply\Application\Gateway;

interface AttachmentLockRepositoryInterface
{
    /**
     * 添付資料ロック情報を保存
     *
     * @param int $applyId
     * @param array $attachmentLocks
     * @param int $lastUpdatedBy
     * @return void
     */
    public function save(int $applyId, array $attachmentLocks, int $lastUpdatedBy): void;

    /**
     * 添付資料ロック情報を取得
     *
     * @param int $applyId
     * @return array
     */
    public function findByApplyId(int $applyId): array;
}
