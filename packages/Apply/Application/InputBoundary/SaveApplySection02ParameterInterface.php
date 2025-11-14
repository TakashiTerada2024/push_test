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
interface SaveApplySection02ParameterInterface
{
    public function getApplyId(): int;

    public function setApplyId(int $applyId): void;

    public function getPurposeOfUse(): ?string;

    public function setPurposeOfUse(?string $purposeOfUse): void;

    public function getNeedToUse(): ?string;

    public function setNeedToUse(?string $needToUse): void;

    public function getEthicalReviewStatus(): ?int;

    public function setEthicalReviewStatus(?int $ethicalReviewStatus): void;

    public function getEthicalReviewRemark(): ?string;

    public function setEthicalReviewRemark(?string $ethicalReviewRemark): void;

    public function getEthicalReviewBoardName(): ?string;

    public function setEthicalReviewBoardName(?string $ethicalReviewBoardName): void;

    public function getEthicalReviewBoardCode(): ?string;

    public function setEthicalReviewBoardCode(?string $ethicalReviewBoardCode): void;

    public function getEthicalReviewBoardDate(): ?string;

    public function setEthicalReviewBoardDate(?string $ethicalReviewBoardDate): void;

    public function getAttachment201(): ?SaveAttachmentParameterInterface;

    public function setAttachment201(?SaveAttachmentParameterInterface $attachment201): void;

    public function getAttachment202(): ?SaveAttachmentParameterInterface;

    public function setAttachment202(?SaveAttachmentParameterInterface $attachment202): void;

    public function getAttachment203(): ?SaveAttachmentParameterInterface;

    public function setAttachment203(?SaveAttachmentParameterInterface $attachment203): void;

    public function getAttachment204(): ?SaveAttachmentParameterInterface;

    public function setAttachment204(?SaveAttachmentParameterInterface $attachment204): void;

    public function getAttachment205(): ?SaveAttachmentParameterInterface;

    public function setAttachment205(?SaveAttachmentParameterInterface $attachment205): void;
}
