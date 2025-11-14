<?php

namespace App\Repositories;

use App\Models\ApplicationSkipUrl;
use Carbon\Carbon;

/**
 * 申出スキップURLリポジトリ
 */
class ApplicationSkipUrlRepository
{
    /**
     * 申出スキップURLを保存する
     *
     * @param string $ulid
     * @param int $applyTypeId
     * @param int $userId
     * @param Carbon $expiredAt
     * @return ApplicationSkipUrl
     */
    public function save(string $ulid, int $applyTypeId, int $userId, Carbon $expiredAt): ApplicationSkipUrl
    {
        $skipUrl = new ApplicationSkipUrl([
            'ulid' => $ulid,
            'apply_type_id' => $applyTypeId,
            'created_by' => $userId,
            'expired_at' => $expiredAt,
            'is_used' => false,
        ]);

        $skipUrl->save();

        return $skipUrl;
    }
}
