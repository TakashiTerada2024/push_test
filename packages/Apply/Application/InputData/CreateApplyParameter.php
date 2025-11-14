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

namespace Ncc01\Apply\Application\InputData;

use Ncc01\Apply\Application\InputBoundary\CreateApplyParameterInterface;

/**
 * CreateApplyParameter
 *
 * @package Ncc01\Apply\Application\Input
 */
class CreateApplyParameter implements CreateApplyParameterInterface
{
    /** @var string|null $affiliation 所属 */
    private ?string $affiliation;
    /** @var int|null $applyType  */
    private ?int $applyType;
    /** @var string|null $subject 研究課題名 */
    private ?string $subject;
    /** @var string|null $researchPeriodStart 研究期間、始期。日付を示す文字列 */
    private ?string $researchPeriodStart;
    /** @var string|null $researchPeriodEnd 研究期間、終期。日付を示す文字列 */
    private ?string $researchPeriodEnd;
    /** @var string|null $purposeOfUse 利用目的：必須 */
    private ?string $purposeOfUse;
    /** @var string|null $researchMethod 調査研究の方法：必須 */
    private ?string $researchMethod;
    /** @var string|null $needToUse 全国がん登録情報利用の必要性：必須 */
    private ?string $needToUse;
    /** @var string|null $applicantName 名前：必須 */
    private ?string $applicantName;
    /** @var string|null $applicantNameKana 名前カナ：必須 */
    private ?string $applicantNameKana;
    /** @var string|null $applicantPhoneNumber 電話番号：必須 */
    private ?string $applicantPhoneNumber;
    /** @var string|null $applicantExtensionPhoneNumber 内線番号 */
    private ?string $applicantExtensionPhoneNumber;
    /** @var string|null $questionAtPriorConsultation */
    private ?string $questionAtPriorConsultation;

    public function getApplyType(): ?int
    {
        return $this->applyType;
    }

    public function setApplyType(?int $applyType): void
    {
        $this->applyType = $applyType;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getResearchPeriodStart(): ?string
    {
        return $this->researchPeriodStart;
    }

    public function setResearchPeriodStart(?string $researchPeriodStart): void
    {
        $this->researchPeriodStart = $researchPeriodStart;
    }

    public function getResearchPeriodEnd(): ?string
    {
        return $this->researchPeriodEnd;
    }

    public function setResearchPeriodEnd(?string $researchPeriodEnd): void
    {
        $this->researchPeriodEnd = $researchPeriodEnd;
    }

    public function getPurposeOfUse(): ?string
    {
        return $this->purposeOfUse;
    }

    public function setPurposeOfUse(?string $purposeOfUse): void
    {
        $this->purposeOfUse = $purposeOfUse;
    }

    public function getResearchMethod(): ?string
    {
        return $this->researchMethod;
    }

    public function setResearchMethod(?string $researchMethod): void
    {
        $this->researchMethod = $researchMethod;
    }

    public function getNeedToUse(): ?string
    {
        return $this->needToUse;
    }

    public function setNeedToUse(?string $needToUse): void
    {
        $this->needToUse = $needToUse;
    }

    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    public function setAffiliation(?string $affiliation): void
    {
        $this->affiliation = $affiliation;
    }

    public function getApplicantName(): ?string
    {
        return $this->applicantName;
    }

    public function setApplicantName(?string $applicantName): void
    {
        $this->applicantName = $applicantName;
    }

    public function getApplicantNameKana(): ?string
    {
        return $this->applicantNameKana;
    }

    public function setApplicantNameKana(?string $applicantNameKana): void
    {
        $this->applicantNameKana = $applicantNameKana;
    }

    public function getApplicantPhoneNumber(): ?string
    {
        return $this->applicantPhoneNumber;
    }

    public function setApplicantPhoneNumber(?string $applicantPhoneNumber): void
    {
        $this->applicantPhoneNumber = $applicantPhoneNumber;
    }

    public function getApplicantExtensionPhoneNumber(): ?string
    {
        return $this->applicantExtensionPhoneNumber;
    }

    public function setApplicantExtensionPhoneNumber(?string $applicantExtensionPhoneNumber): void
    {
        $this->applicantExtensionPhoneNumber = $applicantExtensionPhoneNumber;
    }

    public function getQuestionAtPriorConsultation(): ?string
    {
        return $this->questionAtPriorConsultation;
    }

    public function setQuestionAtPriorConsultation(?string $questionAtPriorConsultation): void
    {
        $this->questionAtPriorConsultation = $questionAtPriorConsultation;
    }
}
