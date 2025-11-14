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

/**
 * SaveApplySection04ParameterInterface
 *
 * @package Ncc01\Apply\Application\Gateway
 */
interface SaveApplySection04ParameterInterface
{
    public function getYearOfDiagnoseStart(): ?int;
    public function getYearOfDiagnoseEnd(): ?int;
    public function getAreaPrefectures(): ?array;
    public function getIdcType(): ?int;
    public function getIdcDetail(): ?string;
    public function getIsAliveRequired(): ?int;
    public function getIsAliveDateRequired(): ?int;
    public function getIsCauseOfDeathRequired(): ?int;
    public function getSex(): ?int;
    public function getSexDetail(): ?string;

    public function setYearOfDiagnoseStart(?int $yearOfDiagnoseStart): void;
    public function setYearOfDiagnoseEnd(?int $yearOfDiagnoseEnd): void;
    public function getRangeOfAgeType(): ?int;
    public function getRangeOfAgeDetail(): ?string;

    /**
     * setAreaPrefectures
     *
     * @param int[]|null $areaPrefectures
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAreaPrefectures(?array $areaPrefectures): void;
    public function setIdcType(?int $idcType): void;
    public function setIdcDetail(?string $idcDetail): void;
    public function setIsAliveRequired(?int $isAliveRequired): void;
    public function setIsAliveDateRequired(?int $isAliveDateRequired): void;
    public function setIsCauseOfDeathRequired(?int $isCauseOfDeathRequired): void;
    public function setSex(?int $sex): void;
    public function setSexDetail(?string $sexDetail): void;
    public function setRangeOfAgeType(?int $rangeOfAgeType): void;
    public function setRangeOfAgeDetail(?string $rangeOfAgeDetail): void;
}
