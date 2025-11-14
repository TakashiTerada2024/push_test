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

use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveApplySection02Interface
 *
 * @package Ncc01\Apply\Application\Gateway
 */
interface SaveApplySection03ParameterInterface
{
    public function getAttachment301(): ?SaveAttachmentParameterInterface;

    public function setAttachment301(?SaveAttachmentParameterInterface $attachment301): void;

    public function getAttachment302(): ?SaveAttachmentParameterInterface;

    public function setAttachment302(?SaveAttachmentParameterInterface $attachment302): void;

    public function getAttachment303(): ?SaveAttachmentParameterInterface;

    public function setAttachment303(?SaveAttachmentParameterInterface $attachment303): void;

    public function getNumberOfUsers(): ?int;

    public function setNumberOfUsers(?int $numberOfUsers): void;

    public function getArrayUsers(): array;

    public function setArrayUsers(array $arrayUsers): void;

    public function getApplicantType(): ?int;

    public function setApplicantType(?int $applicantType): void;

    public function getApplicantName(): ?string;

    public function setApplicantName(?string $applicantName): void;

    public function getApplicantAddress(): ?string;

    public function setApplicantAddress(?string $applicantAddress): void;

    public function getApplicantBirthday(): ?string;

    public function setApplicantBirthday(?string $applicantBirthday): void;

    public function getAffiliation(): ?string;

    public function setAffiliation(?string $affiliation): void;
}
