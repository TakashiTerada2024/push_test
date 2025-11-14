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

use Ncc01\Apply\Application\InputBoundary\SaveApplySection03ParameterInterface;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveApplySection03Parameter
 *
 * @package Ncc01\Apply\Application\Input
 */
class SaveApplySection03Parameter implements SaveApplySection03ParameterInterface
{
    /** @var SaveAttachmentParameterInterface|null $attachment301 */
    private $attachment301;
    /** @var SaveAttachmentParameterInterface|null $attachment302 */
    private $attachment302;
    /** @var SaveAttachmentParameterInterface|null $attachment303 */
    private $attachment303;
    /** @var int|null $numberOfUsers 利用人数 */
    private $numberOfUsers;
    /** @var array $arrayUsers */
    private $arrayUsers;
    /** @var int|null $applicantType */
    private $applicantType;
    /** @var string|null $applicantName */
    private $applicantName;
    /** @var string|null $applicantAddress */
    private $applicantAddress;
    /** @var string|null $applicantBirthday */
    private $applicantBirthday;

    private ?string $affiliation;

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment301(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment301;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment301
     */
    public function setAttachment301(?SaveAttachmentParameterInterface $attachment301): void
    {
        $this->attachment301 = $attachment301;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment302(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment302;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment302
     */
    public function setAttachment302(?SaveAttachmentParameterInterface $attachment302): void
    {
        $this->attachment302 = $attachment302;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment303(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment303;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment303
     */
    public function setAttachment303(?SaveAttachmentParameterInterface $attachment303): void
    {
        $this->attachment303 = $attachment303;
    }

    /**
     * @return int|null
     */
    public function getNumberOfUsers(): ?int
    {
        return $this->numberOfUsers;
    }

    /**
     * @param int|null $numberOfUsers
     */
    public function setNumberOfUsers(?int $numberOfUsers): void
    {
        $this->numberOfUsers = $numberOfUsers;
    }

    /**
     * @return array
     */
    public function getArrayUsers(): array
    {
        return $this->arrayUsers;
    }

    /**
     * @param array $arrayUsers
     */
    public function setArrayUsers(array $arrayUsers): void
    {
        $this->arrayUsers = $arrayUsers;
    }

    /**
     * @return int|null
     */
    public function getApplicantType(): ?int
    {
        return $this->applicantType;
    }

    /**
     * @param int|null $applicantType
     */
    public function setApplicantType(?int $applicantType): void
    {
        $this->applicantType = $applicantType;
    }

    /**
     * @return string|null
     */
    public function getApplicantName(): ?string
    {
        return $this->applicantName;
    }

    /**
     * @param string|null $applicantName
     */
    public function setApplicantName(?string $applicantName): void
    {
        $this->applicantName = $applicantName;
    }

    /**
     * @return string|null
     */
    public function getApplicantAddress(): ?string
    {
        return $this->applicantAddress;
    }

    /**
     * @param string|null $applicantAddress
     */
    public function setApplicantAddress(?string $applicantAddress): void
    {
        $this->applicantAddress = $applicantAddress;
    }

    /**
     * @return string|null
     */
    public function getApplicantBirthday(): ?string
    {
        return $this->applicantBirthday;
    }

    /**
     * @param string|null $applicantBirthday
     */
    public function setApplicantBirthday(?string $applicantBirthday): void
    {
        $this->applicantBirthday = $applicantBirthday;
    }

    /**
     * @return string|null
     */
    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    /**
     * @param string|null $affiliation
     */
    public function setAffiliation(?string $affiliation): void
    {
        $this->affiliation = $affiliation;
    }
}
