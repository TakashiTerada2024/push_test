<?php

namespace Ncc01\Apply\Application\Gateway;

interface ScreenLockRepositoryInterface
{
    /**
     * 画面ロック情報を保存
     *
     * @param int $applyId
     * @param array $screenLocks
     * @param int $lastUpdatedBy
     * @return void
     */
    public function save(int $applyId, array $screenLocks, int $lastUpdatedBy): void;

    /**
     * 画面ロック情報を取得
     *
     * @param int $applyId
     * @return array
     */
    public function findByApplyId(int $applyId): array;
}
