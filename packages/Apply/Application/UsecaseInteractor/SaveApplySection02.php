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
use Ncc01\Apply\Application\InputBoundary\SaveApplySection02ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection02Interface;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;

/**
 * SaveApplySection02
 *
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection02 implements SaveApplySection02Interface
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

    public function __invoke(SaveApplySection02ParameterInterface $parameter, int $id): void
    {
        //保存処理
        //申出情報の保存処理
        $this->saveApplyInfo($parameter, $id);
        //添付ファイルの保存処理
        $this->saveAttachments($parameter, $id);
    }

    /**
     * saveApplyInfo
     *
     * @param SaveApplySection02ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveApplyInfo(SaveApplySection02ParameterInterface $parameter, int $id)
    {
        $arrSaveParameter = [
            '2_purpose_of_use' => $parameter->getPurposeOfUse(),
            '2_need_to_use' => $parameter->getNeedToUse(),
            '2_ethical_review_status' => $parameter->getEthicalReviewStatus(),
            '2_ethical_review_remark' => $parameter->getEthicalReviewRemark(),
            '2_ethical_review_board_name' => $parameter->getEthicalReviewBoardName(),
            '2_ethical_review_board_code' => $parameter->getEthicalReviewBoardCode(),
            '2_ethical_review_board_date' => $parameter->getEthicalReviewBoardDate()
        ];
        $this->applyRepository->update($arrSaveParameter, $id);
    }

    /**
     * saveAttachments
     *
     * @param SaveApplySection02ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveAttachments(SaveApplySection02ParameterInterface $parameter, int $id)
    {
        $saveFileParameter201 = $parameter->getAttachment201();
        if (!is_null($saveFileParameter201)) {
            $saveFileParameter201->setApplyId($id);
            $saveFileParameter201->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter201);
        }
        $saveFileParameter202 = $parameter->getAttachment202();
        if (!is_null($saveFileParameter202)) {
            $saveFileParameter202->setApplyId($id);
            $saveFileParameter202->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter202);
        }
        $saveFileParameter203 = $parameter->getAttachment203();
        if (!is_null($saveFileParameter203)) {
            $saveFileParameter203->setApplyId($id);
            $saveFileParameter203->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter203);
        }
        $saveFileParameter204 = $parameter->getAttachment204();
        if (!is_null($saveFileParameter204)) {
            $saveFileParameter204->setApplyId($id);
            $saveFileParameter204->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter204);
        }

        $saveFileParameter205 = $parameter->getAttachment205();
        if (!is_null($saveFileParameter205)) {
            $saveFileParameter205->setApplyId($id);
            $saveFileParameter205->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($saveFileParameter205);
        }
    }
}
