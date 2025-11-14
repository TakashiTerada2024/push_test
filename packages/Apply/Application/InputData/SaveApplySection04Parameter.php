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

use Ncc01\Apply\Application\InputBoundary\SaveApplySection04ParameterInterface;

/**
 * SaveApplySection04Parameter
 *
 * @package Ncc01\Apply\Application\Input
 */
class SaveApplySection04Parameter implements SaveApplySection04ParameterInterface
{
    /** @var int|null $yearOfDiagnoseStart */
    private $yearOfDiagnoseStart;
    /** @var int|null $yearOfDiagnoseEnd */
    private $yearOfDiagnoseEnd;
    /** @var int[]|null $areaPrefectures */
    private $areaPrefectures;
    /** @var int|null $idcType */
    private $idcType;
    /** @var string|null $idcDetail */
    private $idcDetail;
    /** @var int|null $isAliveRequired */
    private $isAliveRequired;
    /** @var int|null $isAliveDateRequired */
    private $isAliveDateRequired;
    /** @var int|null $isCauseOfDeathRequired */
    private $isCauseOfDeathRequired;
    /** @var int|null $sex */
    private $sex;
    /** @var string|null $sexDetail */
    private $sexDetail;

    /** @var int|null $rangeOfAgeType */
    private $rangeOfAgeType;
    /** @var string|null $rangeOfAgeDetail */
    private $rangeOfAgeDetail;

    /**
     * @return array|null
     */
    public function getAreaPrefectures(): ?array
    {
        return $this->areaPrefectures;
    }

    /**
     * @param int[]|null $areaPrefectures
     */
    public function setAreaPrefectures(?array $areaPrefectures): void
    {
        $this->areaPrefectures = $areaPrefectures;
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
     * @param int|null $yearOfDiagnoseStart
     */
    public function setYearOfDiagnoseStart(?int $yearOfDiagnoseStart): void
    {
        $this->yearOfDiagnoseStart = $yearOfDiagnoseStart;
    }

    /**
     * @param int|null $yearOfDiagnoseEnd
     */
    public function setYearOfDiagnoseEnd(?int $yearOfDiagnoseEnd): void
    {
        $this->yearOfDiagnoseEnd = $yearOfDiagnoseEnd;
    }

    /**
     * @param int|null $idcType
     */
    public function setIdcType(?int $idcType): void
    {
        $this->idcType = $idcType;
    }

    /**
     * @param string|null $idcDetail
     */
    public function setIdcDetail(?string $idcDetail): void
    {
        $this->idcDetail = $idcDetail;
    }

    /**
     * @param int|null $isAliveRequired
     */
    public function setIsAliveRequired(?int $isAliveRequired): void
    {
        $this->isAliveRequired = $isAliveRequired;
    }

    /**
     * @param int|null $isAliveDateRequired
     */
    public function setIsAliveDateRequired(?int $isAliveDateRequired): void
    {
        $this->isAliveDateRequired = $isAliveDateRequired;
    }

    /**
     * @param int|null $isCauseOfDeathRequired
     */
    public function setIsCauseOfDeathRequired(?int $isCauseOfDeathRequired): void
    {
        $this->isCauseOfDeathRequired = $isCauseOfDeathRequired;
    }

    /**
     * @param int|null $sex
     */
    public function setSex(?int $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return int|null
     */
    public function getRangeOfAgeType(): ?int
    {
        return $this->rangeOfAgeType;
    }

    /**
     * @param int|null $rangeOfAgeType
     */
    public function setRangeOfAgeType(?int $rangeOfAgeType): void
    {
        $this->rangeOfAgeType = $rangeOfAgeType;
    }

    /**
     * @return string|null
     */
    public function getRangeOfAgeDetail(): ?string
    {
        return $this->rangeOfAgeDetail;
    }

    /**
     * @param string|null $rangeOfAgeDetail
     */
    public function setRangeOfAgeDetail(?string $rangeOfAgeDetail): void
    {
        $this->rangeOfAgeDetail = $rangeOfAgeDetail;
    }

    /**
     * @return string|null
     */
    public function getSexDetail(): ?string
    {
        return $this->sexDetail;
    }

    /**
     * @param string|null $sexDetail
     */
    public function setSexDetail(?string $sexDetail): void
    {
        $this->sexDetail = $sexDetail;
    }
}
