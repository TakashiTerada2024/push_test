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

namespace Ncc01\User\Application\Usecase\ValidatePermissionParameters;

use Ncc01\Apply\Application\Usecase\RetrieveApplicantByIdInterface;
use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\User\Application\Service\ValidatePermissionParameterInterface;
use Ncc01\User\Enterprise\Spec\Permission\ChangeApplyStatus;
use Ncc01\User\Enterprise\Spec\Permission\PermissionSpecInterface;
use Ncc01\User\Enterprise\User;

/**
 * ValidateChangeApplyStatusParameter
 *
 * @package Ncc01\User\Application\Usecase\ValidatePermissionParameters
 */
class ValidateChangeApplyStatusParameter implements ValidatePermissionParameterInterface
{
    /** @var int $applyId */
    private $applyId;
    /** @var ApplyStatus $statusToChange */
    private $statusToChange;
    /** @var RetrieveApplicantByIdInterface $retrieveApplicantById */
    private $retrieveApplicantById;

    /**
     * ValidateChangeApplyStatusParameter constructor.
     * @param RetrieveApplicantByIdInterface $retrieveApplicantById
     */
    public function __construct(RetrieveApplicantByIdInterface $retrieveApplicantById)
    {
        $this->retrieveApplicantById = $retrieveApplicantById;
    }

    public function getPermissionSpec(User $loginUser): PermissionSpecInterface
    {
        $applicant = $this->retrieveApplicantById->__invoke($this->applyId);

        return new ChangeApplyStatus(
            $loginUser->getRole(),
            $loginUser->getId(),
            $applicant->getId(),
            $this->statusToChange
        );
    }

    /**
     * @param int $applyId
     */
    public function setApplyId(int $applyId): void
    {
        $this->applyId = $applyId;
    }

    /**
     * @param int $statusToChange
     */
    public function setStatusToChange(int $statusToChange): void
    {
        $this->statusToChange = new ApplyStatus($statusToChange);
    }
}
