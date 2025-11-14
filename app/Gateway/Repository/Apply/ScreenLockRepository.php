<?php

namespace App\Gateway\Repository\Apply;

use App\Models\ScreenLock;
use Ncc01\Apply\Application\Gateway\ScreenLockRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
 */
class ScreenLockRepository implements ScreenLockRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(int $applyId, array $screenLocks, int $lastUpdatedBy): void
    {
        ScreenLock::where('apply_id', $applyId)->delete();

        // 新しいロックを保存
        $records = [];
        foreach ($screenLocks as $screenCode => $isLocked) {
            $records[] = [
                'apply_id' => $applyId,
                'screen_code' => $screenCode,
                'is_locked' => $isLocked,
                'last_updated_by' => $lastUpdatedBy,
            ];
        }
        ScreenLock::insert($records);
    }

    public function findByApplyId(int $applyId): array
    {
        return ScreenLock::where('apply_id', $applyId)
            ->pluck('is_locked', 'screen_code')
            ->toArray();
    }
}
