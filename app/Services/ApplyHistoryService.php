<?php

namespace App\Services;

use App\Models\ApplyHistory;

class ApplyHistoryService
{
    protected $applyHistory;

    public function __construct(ApplyHistory $applyHistory)
    {
        $this->applyHistory = $applyHistory;
    }

    public function createHistory($applyId, $sourceApplyId): void
    {
        $applyHistory = $this->applyHistory->create([
            'apply_id' => $applyId,
            'source_apply_id' => $sourceApplyId,
        ]);

        foreach ($applyHistory->sourceApplies as $sourceApplyHistory) {
            $this->applyHistory->create([
                'apply_id' => $applyId,
                'source_apply_id' => $sourceApplyHistory->source_apply_id,
            ]);
        }
    }
}
