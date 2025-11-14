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

namespace App\Gateway\Repository\Attachment;

use App\Models\Attachment;
use App\ModelTranslator\AttachmentTranslator;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AttachmentRepository
 * @package App\Repository\Attachment
 */
class AttachmentRepository implements AttachmentRepositoryInterface
{
    /**
     * create
     *
     * @param SaveAttachmentParameterInterface $parameter
     * @param string $path
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function create(SaveAttachmentParameterInterface $parameter, string $path): int
    {
        $model = new Attachment();
        $id = $parameter->getId();
        if (!is_null($id)) {
            $model->id = $id;
        }

        $model->name = $parameter->getClientOriginalName();
        $model->user_id = $parameter->getUserId();
        $model->apply_id = $parameter->getApplyId();
        $model->path = $path;
        $model->attachment_type_id = $parameter->getAttachmentTypeId();
        $model->status = $parameter->getStatus();
        $model->save();

        return $model->id;
    }

    /**
     * find
     *
     * @param int $id
     * @return \Ncc01\Attachment\Enterprise\Entity\Attachment
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function find(int $id): \Ncc01\Attachment\Enterprise\Entity\Attachment
    {
        $translator = new AttachmentTranslator();
        /** @var Attachment $model */
        $model = Attachment::findOrFail($id);
        return $translator->__invoke($model);
    }

    /**
     * findByApplyIds
     *
     * @param array $ids
     * @return Builder
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function findByApplyIds(array $ids): Builder
    {
        return Attachment::whereIn('apply_id', $ids);
    }

    /**
     * findByConditions
     *
     * @param array $conditions
     * @return Builder
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function findByConditions(array $conditions): Builder
    {
        return Attachment::where($conditions);
    }

    /**
     * update
     *
     * @param array $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     */
    public function update(array $parameter, int $id): void
    {
        $model = Attachment::findOrFail($id);
        $model->fill($parameter);
        $model->save();
    }

    /**
     * updateStatusToUploaded
     * attachmentTypeIdのデータをすべて「アップロード済」に変更する
     *
     * @param int $applyId
     * @param int $attachmentTypeId
     * @param array $excludeAttachmentIds
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function updateStatusToUploaded(int $applyId, int $attachmentTypeId, array $excludeAttachmentIds = []): void
    {
        Attachment::where('apply_id', $applyId)
            ->where('attachment_type_id', $attachmentTypeId)
            ->whereNotIn('id', $excludeAttachmentIds)
            ->update(['status' => AttachmentStatuses::UPLOADED]);
    }

    /**
     * delete
     *
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     */
    public function delete(int $id): void
    {
        Attachment::destroy($id);
    }
}
