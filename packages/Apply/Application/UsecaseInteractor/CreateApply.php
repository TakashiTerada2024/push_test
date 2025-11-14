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
use Ncc01\Apply\Application\InputBoundary\CreateApplyParameterInterface;
use Ncc01\Apply\Application\Usecase\CreateApplyInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * CreateApply
 *
 * @package Ncc01\Apply\Application\Usecase
 */
class CreateApply implements CreateApplyInterface
{
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
    }

    public function __invoke(CreateApplyParameterInterface $parameter, ?int $applyId = null): int
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        $arrayParameters = [
            'user_id' => $authenticatedUser->getId(),
            'type_id' => $parameter->getApplyType(),
            'subject' => $parameter->getSubject(),
            'affiliation' => $parameter->getAffiliation(),

            '6_research_period_start' => $parameter->getResearchPeriodStart(),
            '6_research_period_end' => $parameter->getResearchPeriodEnd(),
            '2_purpose_of_use' => $parameter->getPurposeOfUse(),
            '5_research_method' => $parameter->getResearchMethod(),
            '2_need_to_use' => $parameter->getNeedToUse(),
            'question_at_prior_consultation' => $parameter->getQuestionAtPriorConsultation(),

            '10_applicant_name' => $parameter->getApplicantName(),
            '10_applicant_name_kana' => $parameter->getApplicantNameKana(),
            '10_applicant_phone_number' => $parameter->getApplicantPhoneNumber(),
            '10_applicant_extension_phone_number' => $parameter->getApplicantExtensionPhoneNumber()
        ];

        //保存
        if ($applyId) {
            $this->applyRepository->update($arrayParameters, $applyId);
            return $applyId;
        }
        return $this->applyRepository->create($arrayParameters);
    }
}
