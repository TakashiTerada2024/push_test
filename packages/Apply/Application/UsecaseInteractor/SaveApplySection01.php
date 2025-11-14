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

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\InputBoundary\SaveApplySection01ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection01Interface;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;

/**
 * SaveApply
 *
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection01 implements SaveApplySection01Interface
{
    public function __construct(
        private SaveAttachmentInterface $saveAttachment
    ) {
    }

    /**
     * __invoke
     *
     * @param SaveApplySection01ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveApplySection01ParameterInterface $parameter, int $id): void
    {
        $saveFileParameter101 = $parameter->getAttachment101();
        if (!is_null($saveFileParameter101)) {
            $saveFileParameter101->setApplyId($id);
            $saveFileParameter101->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter101);
        }
        $saveFileParameter102 = $parameter->getAttachment102();
        if (!is_null($saveFileParameter102)) {
            $saveFileParameter102->setApplyId($id);
            $saveFileParameter102->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter102);
        }
        $saveFileParameter103 = $parameter->getAttachment103();
        if (!is_null($saveFileParameter103)) {
            $saveFileParameter103->setApplyId($id);
            $saveFileParameter103->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter103);
        }
    }
}
