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

use Ncc01\User\Enterprise\User;

/**
 * Apply
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class Apply
{
    /** @var int $id */
    private $id;
    /** @var ApplyType|null $type */
    private $type;
    /** @var User $applicant */
    private $applicant;
    /** @var ApplyStatus */
    private $status;

    private ?string $summary;

    /**
     * Apply constructor.
     * @param int $id
     * @param ApplyType|null $type
     * @param User $applicant
     * @param int $status
     */
    public function __construct(int $id, ?ApplyType $type, User $applicant, int $status, ?string $summary)
    {
        $this->id = $id;
        $this->type = $type;
        $this->applicant = $applicant;
        $this->status = new ApplyStatus($status);
        $this->summary = $summary;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return ApplyType|null
     */
    public function getType(): ?ApplyType
    {
        return $this->type;
    }

    /**
     * @return User
     */
    public function getApplicant(): User
    {
        return $this->applicant;
    }

    /**
     * @return ApplyStatus
     */
    public function getStatus(): ApplyStatus
    {
        return $this->status;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }
}
