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

namespace App\Http\Requests\Apply;

/**
 * MessageBodyDto
 * 提供可否相談発生時の自動メール送信のための埋め込みパラメータ情報を持つクラス
 *
 * @package App\Http\Requests\Message\Apply
 * @SuppressWarnings(*PHPCPD*)
 */
class MessageBodyDto
{
    /** @var int $applyType */
    private $applyType;
    /** @var string $subject */
    private $subject;
    /** @var string|null $researchPeriodStart */
    private $researchPeriodStart;
    /** @var string|null $researchPeriodEnd */
    private $researchPeriodEnd;
    /** @var string $purposeOfUse */
    private $purposeOfUse;
    /** @var string $researchMethod */
    private $researchMethod;
    /** @var string $needToUse */
    private $needToUse;
    /** @var string $applicantName */
    private $applicantName;
    /** @var string $applicantNameKana */
    private $applicantNameKana;
    /** @var string $affiliation */
    private $affiliation;
    /** @var string $applicantPhoneNumber */
    private $applicantPhoneNumber;
    /** @var string|null $applicantExtensionPhoneNumber */
    private $applicantExtensionPhoneNumber;
    /** @var string|null $applicantExtensionPhoneNumber */
    private $remark;

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string|null
     */
    public function getResearchPeriodStart(): ?string
    {
        return $this->researchPeriodStart;
    }

    /**
     * @param string|null $researchPeriodStart
     */
    public function setResearchPeriodStart(?string $researchPeriodStart): void
    {
        $this->researchPeriodStart = $researchPeriodStart;
    }

    /**
     * @return string|null
     */
    public function getResearchPeriodEnd(): ?string
    {
        return $this->researchPeriodEnd;
    }

    /**
     * @param string|null $researchPeriodEnd
     */
    public function setResearchPeriodEnd(?string $researchPeriodEnd): void
    {
        $this->researchPeriodEnd = $researchPeriodEnd;
    }

    /**
     * @return string
     */
    public function getPurposeOfUse(): string
    {
        return $this->purposeOfUse;
    }

    /**
     * @param string $purposeOfUse
     */
    public function setPurposeOfUse(string $purposeOfUse): void
    {
        $this->purposeOfUse = $purposeOfUse;
    }

    /**
     * @return string
     */
    public function getResearchMethod(): string
    {
        return $this->researchMethod;
    }

    /**
     * @param string $researchMethod
     */
    public function setResearchMethod(string $researchMethod): void
    {
        $this->researchMethod = $researchMethod;
    }

    /**
     * @return string
     */
    public function getNeedToUse(): string
    {
        return $this->needToUse;
    }

    /**
     * @param string $needToUse
     */
    public function setNeedToUse(string $needToUse): void
    {
        $this->needToUse = $needToUse;
    }

    /**
     * @return string
     */
    public function getApplicantName(): string
    {
        return $this->applicantName;
    }

    /**
     * @param string $applicantName
     */
    public function setApplicantName(string $applicantName): void
    {
        $this->applicantName = $applicantName;
    }

    /**
     * @return string
     */
    public function getApplicantNameKana(): string
    {
        return $this->applicantNameKana;
    }

    /**
     * @param string $applicantNameKana
     */
    public function setApplicantNameKana(string $applicantNameKana): void
    {
        $this->applicantNameKana = $applicantNameKana;
    }

    /**
     * @return string
     */
    public function getAffiliation(): string
    {
        return $this->affiliation;
    }

    /**
     * @param string $affiliation
     */
    public function setAffiliation(string $affiliation): void
    {
        $this->affiliation = $affiliation;
    }

    /**
     * @return string
     */
    public function getApplicantPhoneNumber(): string
    {
        return $this->applicantPhoneNumber;
    }

    /**
     * @param string $applicantPhoneNumber
     */
    public function setApplicantPhoneNumber(string $applicantPhoneNumber): void
    {
        $this->applicantPhoneNumber = $applicantPhoneNumber;
    }

    /**
     * @return string|null
     */
    public function getApplicantExtensionPhoneNumber(): ?string
    {
        return $this->applicantExtensionPhoneNumber;
    }

    /**
     * @param string|null $applicantExtensionPhoneNumber
     */
    public function setApplicantExtensionPhoneNumber(?string $applicantExtensionPhoneNumber): void
    {
        $this->applicantExtensionPhoneNumber = $applicantExtensionPhoneNumber;
    }

    /**
     * @return string|null
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @param string|null $remark
     */
    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return int
     */
    public function getApplyType(): int
    {
        return $this->applyType;
    }

    /**
     * @param int $applyType
     */
    public function setApplyType(int $applyType): void
    {
        $this->applyType = $applyType;
    }
}
