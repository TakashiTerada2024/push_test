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

use Ncc01\Apply\Application\InputBoundary\SaveApplySection10ParameterInterface;

/**
 * Class SaveApplySection08Parameter
 * @package Ncc01\Apply\Application\Input
 */
class SaveApplySection10Parameter implements SaveApplySection10ParameterInterface
{
    /** @var string|null $remark */
    private $remark;
    private ?string $clerkName;
    private ?string $clerkContactAddress;
    private ?string $clerkContactEmail;
    private ?string $clerkContactPhoneNumber;
    private ?string $clerkContactExtensionPhoneNumber;


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
     * @return string|null
     */
    public function getClerkName(): ?string
    {
        return $this->clerkName;
    }

    /**
     * @param string|null $clerkName
     */
    public function setClerkName(?string $clerkName): void
    {
        $this->clerkName = $clerkName;
    }

    /**
     * @return string|null
     */
    public function getClerkContactAddress(): ?string
    {
        return $this->clerkContactAddress;
    }

    /**
     * @param string|null $clerkContactAddress
     */
    public function setClerkContactAddress(?string $clerkContactAddress): void
    {
        $this->clerkContactAddress = $clerkContactAddress;
    }

    /**
     * @return string|null
     */
    public function getClerkContactEmail(): ?string
    {
        return $this->clerkContactEmail;
    }

    /**
     * @param string|null $clerkContactEmail
     */
    public function setClerkContactEmail(?string $clerkContactEmail): void
    {
        $this->clerkContactEmail = $clerkContactEmail;
    }

    /**
     * @return string|null
     */
    public function getClerkContactPhoneNumber(): ?string
    {
        return $this->clerkContactPhoneNumber;
    }

    /**
     * @param string|null $clerkContactPhoneNumber
     */
    public function setClerkContactPhoneNumber(?string $clerkContactPhoneNumber): void
    {
        $this->clerkContactPhoneNumber = $clerkContactPhoneNumber;
    }

    /**
     * @return string|null
     */
    public function getClerkContactExtensionPhoneNumber(): ?string
    {
        return $this->clerkContactExtensionPhoneNumber;
    }

    /**
     * @param string|null $clerkContactExtensionPhoneNumber
     */
    public function setClerkContactExtensionPhoneNumber(?string $clerkContactExtensionPhoneNumber): void
    {
        $this->clerkContactExtensionPhoneNumber = $clerkContactExtensionPhoneNumber;
    }
}
