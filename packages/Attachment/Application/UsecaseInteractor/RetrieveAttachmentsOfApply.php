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

namespace Ncc01\Attachment\Application\UsecaseInteractor;

use Ncc01\Attachment\Application\QueryServiceInterface\RetrieveAttachmentsOfApplyQueryInterface;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentsOfApplyInterface;

/**
 * ShowAttachmentsOfApplyQuery
 *
 * @package Ncc01\Attachment\Application\Usecase
 * @TODO 使用されていないことを確認して削除する
 */
class RetrieveAttachmentsOfApply implements RetrieveAttachmentsOfApplyInterface
{
    /**
     * RetrieveAttachmentsOfApply constructor.
     * @param RetrieveAttachmentsOfApplyQueryInterface $applyQuery
     */
    public function __construct(
        private RetrieveAttachmentsOfApplyQueryInterface $applyQuery
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @param bool|null $isGroup
     * @param array $roleList
     * @param array $statusList
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId, ?bool $isGroup = null, array $roleList = [], array $statusList = []): array
    {
        return $this->applyQuery->__invoke(
            applyId: $applyId,
            isGroup: $isGroup,
            roleList: $roleList,
            statusList: $statusList
        );
    }
}
