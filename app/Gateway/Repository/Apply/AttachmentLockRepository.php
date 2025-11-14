<?php

namespace App\Gateway\Repository\Apply;

use App\Models\AttachmentLock;
use Ncc01\Apply\Application\Gateway\AttachmentLockRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
 */
class AttachmentLockRepository implements AttachmentLockRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(int $applyId, array $attachmentLocks, int $lastUpdatedBy): void
    {
        AttachmentLock::where('apply_id', $applyId)->delete();

        // 新しいロックを保存
        $records = [];
        foreach ($attachmentLocks as $attachmentTypeId => $isLocked) {
            $records[] = [
                'apply_id' => $applyId,
                'attachment_type_id' => $attachmentTypeId,
                'is_locked' => $isLocked,
                'last_updated_by' => $lastUpdatedBy,
            ];
        }
        AttachmentLock::insert($records);
    }

    public function findByApplyId(int $applyId): array
    {
        return AttachmentLock::where('apply_id', $applyId)
            ->pluck('is_locked', 'attachment_type_id')
            ->toArray();
    }
}
