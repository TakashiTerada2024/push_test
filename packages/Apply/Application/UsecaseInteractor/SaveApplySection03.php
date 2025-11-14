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
use Ncc01\Apply\Application\Gateway\ApplyUserRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection03ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection03Interface;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;

/**
 * Class SaveApplySection03
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection03 implements SaveApplySection03Interface
{
    /**
     * @param ApplyRepositoryInterface $applyRepository
     * @param SaveAttachmentInterface $saveAttachment
     * @param ApplyUserRepositoryInterface $applyUserRepository
     */
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private SaveAttachmentInterface $saveAttachment,
        private ApplyUserRepositoryInterface $applyUserRepository
    ) {
    }

    /**
     * __invoke
     *
     * @param SaveApplySection03ParameterInterface $parameter
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveApplySection03ParameterInterface $parameter, int $applyId): void
    {
        $this->saveApply($parameter, $applyId);
        $this->saveApplyUsers($parameter, $applyId);
        $this->saveFiles($parameter, $applyId);
    }

    /**
     * saveApply
     *
     * @param SaveApplySection03ParameterInterface $parameter
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveApply(SaveApplySection03ParameterInterface $parameter, int $id)
    {
        $arrayParameter = [
            '3_number_of_users' => $parameter->getNumberOfUsers(),
            '10_applicant_type' => $parameter->getApplicantType(),
            '10_applicant_name' => $parameter->getApplicantName(),
            '10_applicant_address' => $parameter->getApplicantAddress(),
            '10_applicant_birthday' => $parameter->getApplicantBirthday(),
            'affiliation' => $parameter->getAffiliation()
        ];
        $this->applyRepository->update($arrayParameter, $id);
    }

    /**
     * saveApplyUsers
     *
     * @param SaveApplySection03ParameterInterface $parameter
     * @param int $id
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveApplyUsers(SaveApplySection03ParameterInterface $parameter, int $id): array
    {
        $created = [];
        $this->applyUserRepository->delete($id);
        $array = $parameter->getArrayUsers();
        foreach ($array as $arrayApplyUserParameter) {
            $created[] = $this->applyUserRepository->create($id, $arrayApplyUserParameter);
        }
        return $created;
    }

    /**
     * saveFiles
     *
     * @param SaveApplySection03ParameterInterface $parameter
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function saveFiles(SaveApplySection03ParameterInterface $parameter, int $applyId)
    {
        $tmpParameter = $parameter->getAttachment301();
        if (!is_null($tmpParameter)) {
            $tmpParameter->setApplyId($applyId);
            $tmpParameter->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($tmpParameter);
        }

        $tmpParameter = $parameter->getAttachment302();
        if (!is_null($tmpParameter)) {
            $tmpParameter->setApplyId($applyId);
            $tmpParameter->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($tmpParameter);
        }

        $tmpParameter = $parameter->getAttachment303();
        if (!is_null($tmpParameter)) {
            $tmpParameter->setApplyId($applyId);
            $tmpParameter->setStatus(AttachmentStatuses::UPLOADED);
            $this->saveAttachment->__invoke($tmpParameter);
        }
    }
}
