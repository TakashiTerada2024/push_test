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

namespace App\Gateway\Repository\Apply;

use App\Models\Apply as EloquentApply;
use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Entity\Apply;
use Ncc01\Apply\Enterprise\Entity\ApplyType;
use Ncc01\User\Enterprise\User;

/**
 * ApplyRepository
 *
 * @package App\Repository\Apply
 * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
 */
class ApplyRepository implements ApplyRepositoryInterface
{
    /**
     * findById
     *
     * @param int $applyId
     * @return Apply
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     */
    public function findById(int $applyId): Apply
    {
        $apply = EloquentApply::findOrFail($applyId);
        $applicant = new User($apply->user->id, $apply->user->name, $apply->user->role_id);

        $applyId = null;
        if ($apply->type_id) {
            $applyId = new ApplyType($apply->type_id);
        }
        return new Apply($apply->id, $applyId, $applicant, $apply->status, $apply->summary);
    }

    /**
     * findIdsByStatus
     *
     * @param Array $statusList
     * @return Array
     * @author m.shomura <m.shomura@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function findIdsByStatus(array $statusList): array
    {
        return EloquentApply::query()
            ->whereIn('status', $statusList)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public function cloneApplyById(int $applyId): EloquentApply
    {
        $apply = EloquentApply::find($applyId);
        $newApply = $apply->replicate();
        $newApply->created_at = now();
        $newApply->updated_at = now();
        $newApply->status = ApplyStatuses::CREATING_DOCUMENT;
        $newApply->accepted_at = null;
        $newApply->submitted_at = null;
        $newApply->save();
        return $newApply;
    }

    /**
     * save
     *
     * @param array $parameters
     * @param null $applyId
     * @return void
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function update(array $parameters, $applyId): void
    {
        $model = EloquentApply::findOrFail($applyId);
        $model->fill($parameters);
        $model->update();
    }

    /**
     * create
     *
     * @param array $parameters
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function create(array $parameters): int
    {
        $model = new EloquentApply();
        $model->fill($parameters);
        $model->save();
        return $model->id;
    }

    public function existAccepted(int $applyId): bool
    {
        return EloquentApply::query()->where([
            'id' => $applyId,
            'status' => ApplyStatuses::ACCEPTED,
        ])->exists();
    }
}
