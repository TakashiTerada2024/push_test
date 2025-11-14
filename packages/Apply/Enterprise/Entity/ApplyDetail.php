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

namespace Ncc01\Apply\Enterprise\Entity;

use Carbon\Carbon;
use Ncc01\Common\Enterprise\Classification\Prefectures;

/**
 * ApplyDetail
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class ApplyDetail
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength) これ以上短くするにはクラスの分割が必要
     */
    public function __construct(
        private int $id,
        private int $userId,
        private ?ApplyType $type,
        private ?string $subject,
        private ?string $affiliation,
        private ?string $department,
        private ApplyStatus $status,
        private ?string $purposeOfUse,
        private ?string $needToUse,
        private ?int $ethicalReviewStatus,
        private ?string $ethicalReviewRemark,
        private ?string $ethicalReviewBoardName,
        private ?string $ethicalReviewBoardCode,
        private ?Carbon $ethicalReviewBoardDate,
        private ?int $numberOfUsers,
        private ?int $yearOfDiagnoseStart,
        private ?int $yearOfDiagnoseEnd,
        private ?array $areaPrefectures,
        private ?int $idcType,
        private ?string $idcDetail,
        private ?int $isAliveRequired,
        private ?int $isAliveDateRequired,
        private ?int $isCauseOfDeathRequired,
        private ?int $sex,
        private ?string $sexDetail,
        private ?int $rangeOfAgeType,
        private ?string $rangeOfAgeDetail,
        private ?string $researchMethod,
        private ?Carbon $usagePeriodEnd,
        private ?Carbon $researchPeriodStart,
        private ?Carbon $researchPeriodEnd,
        private ?string $scheduledToBeAnnounced,
        private ?string $treatmentAfterUse,
        private ?string $clerkName,
        private ?string $clerkContactAddress,
        private ?string $clerkContactEmail,
        private ?string $clerkContactPhoneNumber,
        private ?string $clerkContactExtensionPhoneNumber,
        private ?int $applicantType,
        private ?string $applicantName,
        private ?string $applicantAddress,
        private ?Carbon $applicantBirthday,
        private ?string $remark,
        private ?string $applicantNameKana,
        private ?string $applicantPhoneNumber,
        private ?string $applicantExtensionPhoneNumber,
        private ?string $summary,
        private ?Carbon $submittedAt,
        private ?iterable $copiedApplies
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return ApplyType|null
     */
    public function getType(): ?ApplyType
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return string|null
     */
    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    /**
     * @return string|null
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }

    /**
     * @return ApplyStatus
     */
    public function getStatus(): ApplyStatus
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getPurposeOfUse(): ?string
    {
        return $this->purposeOfUse;
    }

    /**
     * @return string|null
     */
    public function getNeedToUse(): ?string
    {
        return $this->needToUse;
    }

    /**
     * @return int|null
     */
    public function getEthicalReviewStatus(): ?int
    {
        return $this->ethicalReviewStatus;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewRemark(): ?string
    {
        return $this->ethicalReviewRemark;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewBoardName(): ?string
    {
        return $this->ethicalReviewBoardName;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewBoardCode(): ?string
    {
        return $this->ethicalReviewBoardCode;
    }

    /**
     * @return Carbon|null
     */
    public function getEthicalReviewBoardDate(): ?Carbon
    {
        return $this->ethicalReviewBoardDate;
    }

    /**
     * @return int|null
     */
    public function getNumberOfUsers(): ?int
    {
        return $this->numberOfUsers;
    }

    /**
     * @return int|null
     */
    public function getYearOfDiagnoseStart(): ?int
    {
        return $this->yearOfDiagnoseStart;
    }

    /**
     * @return int|null
     */
    public function getYearOfDiagnoseEnd(): ?int
    {
        return $this->yearOfDiagnoseEnd;
    }

    public function getAreaPrefectures(): ?array
    {
        return $this->areaPrefectures;
    }

    /**
     * @return int|null
     */
    public function getIdcType(): ?int
    {
        return $this->idcType;
    }

    /**
     * @return string|null
     */
    public function getIdcDetail(): ?string
    {
        return $this->idcDetail;
    }

    /**
     * @return int|null
     */
    public function getIsAliveRequired(): ?int
    {
        return $this->isAliveRequired;
    }

    /**
     * @return int|null
     */
    public function getIsAliveDateRequired(): ?int
    {
        return $this->isAliveDateRequired;
    }

    /**
     * @return int|null
     */
    public function getIsCauseOfDeathRequired(): ?int
    {
        return $this->isCauseOfDeathRequired;
    }

    /**
     * @return int|null
     */
    public function getSex(): ?int
    {
        return $this->sex;
    }

    /**
     * @return string|null
     */
    public function getResearchMethod(): ?string
    {
        return $this->researchMethod;
    }

    /**
     * @return Carbon|null
     */
    public function getUsagePeriodEnd(): ?Carbon
    {
        return $this->usagePeriodEnd;
    }

    /**
     * @return Carbon|null
     */
    public function getResearchPeriodStart(): ?Carbon
    {
        return $this->researchPeriodStart;
    }

    public function getResearchPeriodEnd(): ?Carbon
    {
        return $this->researchPeriodEnd;
    }

    public function getScheduledToBeAnnounced(): ?string
    {
        return $this->scheduledToBeAnnounced;
    }

    public function getTreatmentAfterUse(): ?string
    {
        return $this->treatmentAfterUse;
    }

    public function getClerkName(): ?string
    {
        return $this->clerkName;
    }

    public function getClerkContactAddress(): ?string
    {
        return $this->clerkContactAddress;
    }

    public function getApplicantType(): ?int
    {
        return $this->applicantType;
    }

    public function getApplicantName(): ?string
    {
        return $this->applicantName;
    }

    public function getApplicantAddress(): ?string
    {
        return $this->applicantAddress;
    }

    public function getApplicantBirthday(): ?Carbon
    {
        return $this->applicantBirthday;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function getApplicantNameKana(): ?string
    {
        return $this->applicantNameKana;
    }

    public function getApplicantPhoneNumber(): ?string
    {
        return $this->applicantPhoneNumber;
    }

    public function getApplicantExtensionPhoneNumber(): ?string
    {
        return $this->applicantExtensionPhoneNumber;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @return int|null
     */
    public function getRangeOfAgeType(): ?int
    {
        return $this->rangeOfAgeType;
    }

    /**
     * @return string|null
     */
    public function getRangeOfAgeDetail(): ?string
    {
        return $this->rangeOfAgeDetail;
    }

    /**
     * @return string|null
     */
    public function getSexDetail(): ?string
    {
        return $this->sexDetail;
    }

    /**
     * @return string|null
     */
    public function getClerkContactEmail(): ?string
    {
        return $this->clerkContactEmail;
    }

    /**
     * @return string|null
     */
    public function getClerkContactPhoneNumber(): ?string
    {
        return $this->clerkContactPhoneNumber;
    }

    /**
     * @return string|null
     */
    public function getClerkContactExtensionPhoneNumber(): ?string
    {
        return $this->clerkContactExtensionPhoneNumber;
    }

    /**
     * @return bool
     */
    public function isAllPrefectures(Prefectures $prefectures = null): bool
    {
        if (empty($prefectures)) {
            $prefectures = new Prefectures();
        }

        foreach ($prefectures->keys() as $id) {
            if (!in_array($id, ($this->getAreaPrefectures() ?? []))) {
                // 選択されていないprefがあった
                return false;
            }
        }

        return true;
    }

    /**
     * get submitted at
     *
     * @return Carbon|null
     * @author anhpd
     */
    public function getSubmittedAt(): ?Carbon
    {
        return $this->submittedAt;
    }

    /**
     * getCopiedApplies
     *
     * @param int $applyId
     * @return iterable|null
     * @author anhpd
     */
    public function getCopiedApplies(): ?iterable
    {
        return $this->copiedApplies;
    }
}
