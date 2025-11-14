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

namespace Ncc01\Apply\Application\OutputData;

use LogicException;
use Ncc01\Apply\Application\OutputBoundary\ApplyBaseInfoInterface;
use Ncc01\Apply\Enterprise\Entity\Apply;

class ApplyBaseInfo implements ApplyBaseInfoInterface
{
    public function __construct(private Apply $apply)
    {
    }

    public function getId(): int
    {
        return $this->apply->getId();
    }

    public function getStatusId(): int
    {
        return $this->apply->getStatus()->getValue();
    }

    public function getTypeId(): ?int
    {
        return $this->apply->getType()?->getValue();
    }

    public function getStatusName(): string
    {
        return $this->apply->getStatus()->getName();
    }

    public function getSummary(): string|null
    {
        return $this->apply->getSummary();
    }

    public function isLinkage(): bool
    {
        $applyType = $this->apply->getType();
        if (is_null($applyType)) {
            throw new LogicException('');
        }
        return $applyType->isLinkage();
    }

    public function getApplicantUserId(): int
    {
        return $this->apply->getApplicant()->getId();
    }

    public function isPriorConsultation(): bool
    {
        return $this->apply->getStatus()->isPriorConsultation();
    }
}
