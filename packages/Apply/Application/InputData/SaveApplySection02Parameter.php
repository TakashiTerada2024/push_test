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

use Ncc01\Apply\Application\InputBoundary\SaveApplySection02ParameterInterface;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveApplySection02Parameter
 *
 * @package Ncc01\Apply\Application\Input
 */
class SaveApplySection02Parameter implements SaveApplySection02ParameterInterface
{
    /** @var int $applyId */
    private $applyId;
    /** @var string|null $purposeOfUse */
    private $purposeOfUse;
    /** @var string|null $needToUse */
    private $needToUse;
    /** @var int|null $ethicalReviewStatus */
    private $ethicalReviewStatus;
    /** @var string|null $ethicalReviewRemark */
    private $ethicalReviewRemark;
    /** @var string|null $ethicalReviewBoardName */
    private $ethicalReviewBoardName;
    /** @var string|null $purposeOfUse */
    private $ethicalReviewBoardCode;
    /** @var string|null $ethicalReviewBoardDate */
    private $ethicalReviewBoardDate;

    /** @var SaveAttachmentParameterInterface|null $attachment201 */
    private $attachment201;
    /** @var SaveAttachmentParameterInterface|null $attachment202 */
    private $attachment202;
    /** @var SaveAttachmentParameterInterface|null $attachment203 */
    private $attachment203;
    /** @var SaveAttachmentParameterInterface|null $attachment204 */
    private $attachment204;

    private ?SaveAttachmentParameterInterface $attachment205;

    /**
     * @return int
     */
    public function getApplyId(): int
    {
        return $this->applyId;
    }

    /**
     * @param int $applyId
     */
    public function setApplyId(int $applyId): void
    {
        $this->applyId = $applyId;
    }

    /**
     * @return string|null
     */
    public function getPurposeOfUse(): ?string
    {
        return $this->purposeOfUse;
    }

    /**
     * @param string|null $purposeOfUse
     */
    public function setPurposeOfUse(?string $purposeOfUse): void
    {
        $this->purposeOfUse = $purposeOfUse;
    }

    /**
     * @return string|null
     */
    public function getNeedToUse(): ?string
    {
        return $this->needToUse;
    }

    /**
     * @param string|null $needToUse
     */
    public function setNeedToUse(?string $needToUse): void
    {
        $this->needToUse = $needToUse;
    }

    /**
     * @return int|null
     */
    public function getEthicalReviewStatus(): ?int
    {
        return $this->ethicalReviewStatus;
    }

    /**
     * @param int|null $ethicalReviewStatus
     */
    public function setEthicalReviewStatus(?int $ethicalReviewStatus): void
    {
        $this->ethicalReviewStatus = $ethicalReviewStatus;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewRemark(): ?string
    {
        return $this->ethicalReviewRemark;
    }

    /**
     * @param string|null $ethicalReviewRemark
     */
    public function setEthicalReviewRemark(?string $ethicalReviewRemark): void
    {
        $this->ethicalReviewRemark = $ethicalReviewRemark;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewBoardName(): ?string
    {
        return $this->ethicalReviewBoardName;
    }

    /**
     * @param string|null $ethicalReviewBoardName
     */
    public function setEthicalReviewBoardName(?string $ethicalReviewBoardName): void
    {
        $this->ethicalReviewBoardName = $ethicalReviewBoardName;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewBoardCode(): ?string
    {
        return $this->ethicalReviewBoardCode;
    }

    /**
     * @param string|null $ethicalReviewBoardCode
     */
    public function setEthicalReviewBoardCode(?string $ethicalReviewBoardCode): void
    {
        $this->ethicalReviewBoardCode = $ethicalReviewBoardCode;
    }

    /**
     * @return string|null
     */
    public function getEthicalReviewBoardDate(): ?string
    {
        return $this->ethicalReviewBoardDate;
    }

    /**
     * @param string|null $ethicalReviewBoardDate
     */
    public function setEthicalReviewBoardDate(?string $ethicalReviewBoardDate): void
    {
        $this->ethicalReviewBoardDate = $ethicalReviewBoardDate;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment201(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment201;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment201
     */
    public function setAttachment201(?SaveAttachmentParameterInterface $attachment201): void
    {
        $this->attachment201 = $attachment201;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment202(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment202;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment202
     */
    public function setAttachment202(?SaveAttachmentParameterInterface $attachment202): void
    {
        $this->attachment202 = $attachment202;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment203(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment203;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment203
     */
    public function setAttachment203(?SaveAttachmentParameterInterface $attachment203): void
    {
        $this->attachment203 = $attachment203;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment204(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment204;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment204
     */
    public function setAttachment204(?SaveAttachmentParameterInterface $attachment204): void
    {
        $this->attachment204 = $attachment204;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment205(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment205;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment205
     */
    public function setAttachment205(?SaveAttachmentParameterInterface $attachment205): void
    {
        $this->attachment205 = $attachment205;
    }
}
