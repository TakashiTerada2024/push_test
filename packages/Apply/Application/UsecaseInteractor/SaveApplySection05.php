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

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection05ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection05Interface;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;

/**
 * SaveApplySection05
 *
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection05 implements SaveApplySection05Interface
{
    /**
     * @param ApplyRepositoryInterface $applyRepository
     * @param SaveAttachmentInterface $saveAttachment
     */
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private SaveAttachmentInterface $saveAttachment
    ) {
    }

    /**
     * __invoke
     *
     * @param SaveApplySection05ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveApplySection05ParameterInterface $parameter, int $id): void
    {
        //申出情報の保存処理
        $this->saveApplyInfo($parameter, $id);
        //添付ファイルの保存処理
        $this->saveAttachments($parameter, $id);
    }

    /**
     * saveApplyInfo
     *
     * @param SaveApplySection05ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveApplyInfo(SaveApplySection05ParameterInterface $parameter, int $id)
    {
        $arrSaveParameter = [
            '5_research_method' => $parameter->getResearchMethod()
        ];
        $this->applyRepository->update($arrSaveParameter, $id);
    }

    /**
     * saveAttachments
     *
     * @param SaveApplySection05ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveAttachments(SaveApplySection05ParameterInterface $parameter, int $id)
    {
        $attachment501Parameter = $parameter->getAttachment501();
        if (!is_null($attachment501Parameter)) {
            // 入力内容を保存
            $attachment501Parameter->setApplyId($id);
            $attachment501Parameter->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($attachment501Parameter);
        }

        $attachment502Parameter = $parameter->getAttachment502();
        if (!is_null($attachment502Parameter)) {
            // 入力内容を保存
            $attachment502Parameter->setApplyId($id);
            $attachment502Parameter->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($attachment502Parameter);
        }
    }
}
