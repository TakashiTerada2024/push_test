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

namespace Ncc01\Apply\Application\Service\Builder;

use Carbon\Carbon;
use Ncc01\Apply\Enterprise\Entity\ApplyDetail;
use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\Apply\Enterprise\Entity\ApplyType;

/**
 * ApplyDetailBuilder
 *
 * @package Ncc01\Apply\Application\Service\Builder
 */
class ApplyDetailBuilder
{
    private int $id;
    private int $userId;
    private ?ApplyType $type;
    private ?string $subject;
    private ?string $affiliation;
    private ?string $department;
    private ApplyStatus $status;
    private ?string $purposeOfUse;
    private ?string $needToUse;
    private ?int $ethicalReviewStatus;
    private ?string $ethicalReviewRemark;
    private ?string $ethicalReviewBoardName;
    private ?string $ethicalReviewBoardCode;
    private ?Carbon $ethicalReviewBoardDate;
    private ?int $numberOfUsers;
    private ?int $yearOfDiagnoseStart;
    private ?int $yearOfDiagnoseEnd;
    private ?array $areaPrefectures;
    private ?int $idcType;
    private ?string $idcDetail;
    private ?int $isAliveRequired;
    private ?int $isAliveDateRequired;
    private ?int $isCauseOfDeathRequired;
    private ?int $sex;
    private ?string $sexDetail;
    private ?int $rangeOfAgeType;
    private ?string $rangeOfAgeDetail;
    private ?string $researchMethod;
    private ?Carbon $usagePeriodEnd;
    private ?Carbon $researchPeriodStart;
    private ?Carbon $researchPeriodEnd;
    private ?string $scheduledToBeAnnounced;
    private ?string $treatmentAfterUse;
    private ?string $clerkName;
    private ?string $clerkContactAddress;
    private ?string $clerkContactEmail;
    private ?string $clerkContactPhoneNumber;
    private ?string $clerkContactExtensionPhoneNumber;
    private ?int $applicantType;
    private ?string $applicantName;
    private ?string $applicantAddress;
    private ?Carbon $applicantBirthday;
    private ?string $remark;
    private ?string $applicantNameKana;
    private ?string $applicantPhoneNumber;
    private ?string $applicantExtensionPhoneNumber;
    private ?string $summary;
    private ?Carbon $submittedAt;
    private ?iterable $copiedApplies;

    /**
     * setId
     *
     * @param int $id
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setType(?int $typeId): self
    {
        if (!is_null($typeId)) {
            $this->type = new ApplyType($typeId);
        }
        return $this;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function setAffiliation(?string $affiliation): self
    {
        $this->affiliation = $affiliation;
        return $this;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = new ApplyStatus($status);
        return $this;
    }

    public function setPurposeOfUse(?string $purposeOfUse): self
    {
        $this->purposeOfUse = $purposeOfUse;
        return $this;
    }

    public function setNeedToUse(?string $needToUse): self
    {
        $this->needToUse = $needToUse;
        return $this;
    }

    public function setEthicalReviewStatus(?int $ethicalReviewStatus): self
    {
        $this->ethicalReviewStatus = $ethicalReviewStatus;
        return $this;
    }

    public function setEthicalReviewRemark(?string $ethicalReviewRemark): self
    {
        $this->ethicalReviewRemark = $ethicalReviewRemark;
        return $this;
    }

    public function setEthicalReviewBoardName(?string $ethicalReviewBoardName): self
    {
        $this->ethicalReviewBoardName = $ethicalReviewBoardName;
        return $this;
    }

    public function setEthicalReviewBoardCode(?string $ethicalReviewBoardCode): self
    {
        $this->ethicalReviewBoardCode = $ethicalReviewBoardCode;
        return $this;
    }

    public function setEthicalReviewBoardDate(?string $ethicalReviewBoardDate): self
    {
        $this->ethicalReviewBoardDate = ($ethicalReviewBoardDate ? new Carbon($ethicalReviewBoardDate) : null);
        return $this;
    }

    public function setNumberOfUsers(?int $numberOfUsers): self
    {
        $this->numberOfUsers = $numberOfUsers;
        return $this;
    }

    public function setYearOfDiagnoseStart(?int $yearOfDiagnoseStart): self
    {
        $this->yearOfDiagnoseStart = $yearOfDiagnoseStart;
        return $this;
    }

    public function setYearOfDiagnoseEnd(?int $yearOfDiagnoseEnd): self
    {
        $this->yearOfDiagnoseEnd = $yearOfDiagnoseEnd;
        return $this;
    }

    public function setAreaPrefectures(?array $areaPrefectures): self
    {
        $this->areaPrefectures = $areaPrefectures;
        return $this;
    }

    public function setIdcType(?int $idcType): self
    {
        $this->idcType = $idcType;
        return $this;
    }

    public function setIdcDetail(?string $idcDetail): self
    {
        $this->idcDetail = $idcDetail;
        return $this;
    }

    public function setIsAliveRequired(?int $isAliveRequired): self
    {
        $this->isAliveRequired = $isAliveRequired;
        return $this;
    }

    public function setIsAliveDateRequired(?int $isAliveDateRequired): self
    {
        $this->isAliveDateRequired = $isAliveDateRequired;
        return $this;
    }

    public function setIsCauseOfDeathRequired(?int $isCauseOfDeathRequired): self
    {
        $this->isCauseOfDeathRequired = $isCauseOfDeathRequired;
        return $this;
    }

    public function setSex(?int $sex): self
    {
        $this->sex = $sex;
        return $this;
    }

    public function setResearchMethod(?string $researchMethod): self
    {
        $this->researchMethod = $researchMethod;
        return $this;
    }

    public function setUsagePeriodEnd(?string $usagePeriodEnd): self
    {
        $this->usagePeriodEnd = ($usagePeriodEnd ? new Carbon($usagePeriodEnd) : null);
        return $this;
    }

    public function setResearchPeriodStart(?string $researchPeriodStart): self
    {
        $this->researchPeriodStart = ($researchPeriodStart ? new Carbon($researchPeriodStart) : null);
        return $this;
    }

    public function setResearchPeriodEnd(?string $researchPeriodEnd): self
    {
        $this->researchPeriodEnd = ($researchPeriodEnd ? new Carbon($researchPeriodEnd) : null);
        return $this;
    }

    public function setScheduledToBeAnnounced(?string $scheduledToBeAnnounced): self
    {
        $this->scheduledToBeAnnounced = $scheduledToBeAnnounced;
        return $this;
    }

    public function setTreatmentAfterUse(?string $treatmentAfterUse): self
    {
        $this->treatmentAfterUse = $treatmentAfterUse;
        return $this;
    }

    public function setClerkName(?string $clerkName): self
    {
        $this->clerkName = $clerkName;
        return $this;
    }

    public function setClerkContactAddress(?string $clerkContactAddress): self
    {
        $this->clerkContactAddress = $clerkContactAddress;
        return $this;
    }

    public function setApplicantType(?int $applicantType): self
    {
        $this->applicantType = $applicantType;
        return $this;
    }

    public function setApplicantName(?string $applicantName): self
    {
        $this->applicantName = $applicantName;
        return $this;
    }

    public function setApplicantAddress(?string $applicantAddress): self
    {
        $this->applicantAddress = $applicantAddress;
        return $this;
    }

    public function setApplicantBirthday(?string $applicantBirthday): self
    {
        $this->applicantBirthday = ($applicantBirthday ? new Carbon($applicantBirthday) : null);
        return $this;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;
        return $this;
    }

    public function setApplicantNameKana(?string $applicantNameKana): self
    {
        $this->applicantNameKana = $applicantNameKana;
        return $this;
    }

    public function setApplicantPhoneNumber(?string $applicantPhoneNumber): self
    {
        $this->applicantPhoneNumber = $applicantPhoneNumber;
        return $this;
    }

    public function setApplicantExtensionPhoneNumber(?string $applicantExtensionPhoneNumber): self
    {
        $this->applicantExtensionPhoneNumber = $applicantExtensionPhoneNumber;
        return $this;
    }

    /**
     * setRangeOfAgeType
     *
     * @param int|null $rangeOfAgeType
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setRangeOfAgeType(?int $rangeOfAgeType): self
    {
        $this->rangeOfAgeType = $rangeOfAgeType;
        return $this;
    }

    /**
     * setRangeOfAgeDetail
     *
     * @param string|null $rangeOfAgeDetail
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setRangeOfAgeDetail(?string $rangeOfAgeDetail): self
    {
        $this->rangeOfAgeDetail = $rangeOfAgeDetail;
        return $this;
    }

    /**
     * set summary
     *
     * @param string|null $summary
     * @return $this
     * @author anhpd
     */
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * setSubmittedAt
     *
     * @param string|null $submittedAt
     * @return $this
     * @author anhpd
     */
    public function setSubmittedAt(?string $submittedAt): self
    {
        $this->submittedAt = $submittedAt ? new Carbon($submittedAt) : null;
        return $this;
    }

    public function setCopiedApplies(?iterable $copiedApplies): self
    {
        $this->copiedApplies = $copiedApplies;
        return $this;
    }

    /**
     * __invoke
     *
     * @return ApplyDetail
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength) これ以上短くすることは不可能
     */
    public function __invoke(): ApplyDetail
    {
        return new ApplyDetail(
            $this->id,
            $this->userId,
            $this->type,
            $this->subject,
            $this->affiliation,
            $this->department,
            $this->status,
            $this->purposeOfUse,
            $this->needToUse,
            $this->ethicalReviewStatus,
            $this->ethicalReviewRemark,
            $this->ethicalReviewBoardName,
            $this->ethicalReviewBoardCode,
            $this->ethicalReviewBoardDate,
            $this->numberOfUsers,
            $this->yearOfDiagnoseStart,
            $this->yearOfDiagnoseEnd,
            $this->areaPrefectures,
            $this->idcType,
            $this->idcDetail,
            $this->isAliveRequired,
            $this->isAliveDateRequired,
            $this->isCauseOfDeathRequired,
            $this->sex,
            $this->sexDetail,
            $this->rangeOfAgeType,
            $this->rangeOfAgeDetail,
            $this->researchMethod,
            $this->usagePeriodEnd,
            $this->researchPeriodStart,
            $this->researchPeriodEnd,
            $this->scheduledToBeAnnounced,
            $this->treatmentAfterUse,
            $this->clerkName,
            $this->clerkContactAddress,
            $this->clerkContactEmail,
            $this->clerkContactPhoneNumber,
            $this->clerkContactExtensionPhoneNumber,
            $this->applicantType,
            $this->applicantName,
            $this->applicantAddress,
            $this->applicantBirthday,
            $this->remark,
            $this->applicantNameKana,
            $this->applicantPhoneNumber,
            $this->applicantExtensionPhoneNumber,
            $this->summary,
            $this->submittedAt,
            $this->copiedApplies
        );
    }

    /**
     * setSexDetail
     *
     * @param string|null $sexDetail
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setSexDetail(?string $sexDetail): self
    {
        $this->sexDetail = $sexDetail;
        return $this;
    }

    /**
     * setClerkContactEmail
     *
     * @param string|null $clerkContactEmail
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setClerkContactEmail(?string $clerkContactEmail): self
    {
        $this->clerkContactEmail = $clerkContactEmail;
        return $this;
    }

    /**
     * setClerkContactPhoneNumber
     *
     * @param string|null $clerkContactPhoneNumber
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setClerkContactPhoneNumber(?string $clerkContactPhoneNumber): self
    {
        $this->clerkContactPhoneNumber = $clerkContactPhoneNumber;
        return $this;
    }

    /**
     * setClerkContactExtensionPhoneNumber
     *
     * @param string|null $clerkContactExtensionPhoneNumber
     * @return $this
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setClerkContactExtensionPhoneNumber(?string $clerkContactExtensionPhoneNumber): self
    {
        $this->clerkContactExtensionPhoneNumber = $clerkContactExtensionPhoneNumber;
        return $this;
    }
}
