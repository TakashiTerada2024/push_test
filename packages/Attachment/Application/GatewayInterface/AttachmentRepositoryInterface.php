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

namespace Ncc01\Attachment\Application\GatewayInterface;

use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Illuminate\Database\Eloquent\Builder;

/**
 * AttachmentRepositoryInterface
 *
 * @package Ncc01\Attachment\Application\Gateway
 */
interface AttachmentRepositoryInterface
{
    /**
     * create
     *
     * @param SaveAttachmentParameterInterface $parameter
     * @param string $path
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function create(SaveAttachmentParameterInterface $parameter, string $path): int;

    /**
     * find
     *
     * @param int $id
     * @return Attachment
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function find(int $id): Attachment;

    /**
     * findByApplyIds
     *
     * @param array $ids
     * @return Builder
     */
    public function findByApplyIds(array $ids): Builder;

    /**
     * findByConditions
     *
     * @param array $conditions
     * @return Builder
     */
    public function findByConditions(array $conditions): Builder;

    /**
     * update
     *
     * @param array $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function update(array $parameter, int $id): void;

    /**
     * updateStatusToUploaded
     *
     * @param int $applyId
     * @param int $attachmentTypeId
     * @param array $excludeAttachmentIds
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function updateStatusToUploaded(int $applyId, int $attachmentTypeId, array $excludeAttachmentIds = []): void;

    /**
     * delete
     *
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function delete(int $id): void;
}
