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

namespace Ncc01\Apply\Application\InputBoundary;

interface CreateApplyParameterInterface
{
    public function getApplyType(): ?int;
    public function setApplyType(?int $applyType): void;

    public function getSubject(): ?string;
    public function setSubject(?string $subject): void;
    public function getResearchPeriodStart(): ?string;
    public function setResearchPeriodStart(?string $researchPeriodStart): void;
    public function getResearchPeriodEnd(): ?string;
    public function setResearchPeriodEnd(?string $researchPeriodEnd): void;
    public function getPurposeOfUse(): ?string;
    public function setPurposeOfUse(?string $purposeOfUse): void;
    public function getResearchMethod(): ?string;
    public function setResearchMethod(?string $researchMethod): void;
    public function getNeedToUse(): ?string;
    public function setNeedToUse(?string $needToUse): void;
    public function getAffiliation(): ?string;
    public function setAffiliation(?string $affiliation): void;
    public function getApplicantName(): ?string;
    public function setApplicantName(?string $applicantName): void;
    public function getApplicantNameKana(): ?string;
    public function setApplicantNameKana(?string $applicantNameKana): void;
    public function getApplicantPhoneNumber(): ?string;
    public function setApplicantPhoneNumber(?string $applicantPhoneNumber): void;
    public function getApplicantExtensionPhoneNumber(): ?string;
    public function setApplicantExtensionPhoneNumber(?string $applicantExtensionPhoneNumber): void;
    public function getQuestionAtPriorConsultation(): ?string;
    public function setQuestionAtPriorConsultation(?string $questionAtPriorConsultation): void;
}
