<?php

/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Gateway\Repository\ApplyUser;

use App\Models\ApplyUser;
use Ncc01\Apply\Application\Gateway\ApplyUserRepositoryInterface;

/**
 * ApplyUserRepository
 *
 * @package App\Repository\ApplyUser
 */
class ApplyUserRepository implements ApplyUserRepositoryInterface
{
    /**
     * create
     *
     * @param int $applyId
     * @param array $parameter
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function create(int $applyId, array $parameter): int
    {
        $model = new ApplyUser();
        $model->apply_id = $applyId;
        $model->fill($parameter);
        $model->save();

        return $model->id;
    }

    /**
     * cloneByApplyId
     *
     * @param int $sourceApplyId
     * @param int $applyId
     */

    public function cloneBySourceApplyId(int $sourceApplyId, int $applyId)
    {
        $applyUsers = ApplyUser::where('apply_id', $sourceApplyId)->get();

        if (!$applyUsers->count()) {
            return;
        }

        foreach ($applyUsers as $applyUser) {
            $newApplyUser             = $applyUser->replicate();
            $newApplyUser->apply_id   = $applyId;
            $newApplyUser->created_at = now();
            $newApplyUser->updated_at = now();
            $newApplyUser->save();
        }
        return;
    }

    /**
     * delete
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function delete(int $applyId)
    {
        ApplyUser::where('apply_id', $applyId)->delete();
    }

    public function find(int $applyId): array
    {
        return ApplyUser::where('apply_id', $applyId)->get()->toArray();
    }
}
