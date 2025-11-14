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
use DateTimeInterface;

/**
 * ApplicationSkipUrl エンティティ
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class ApplicationSkipUrl
{
    /** @var int ID */
    private ?int $id;

    /** @var string ULID */
    private string $ulid;

    /** @var int 申出種別ID */
    private int $applyTypeId;

    /** @var int 作成者ID（事務局ユーザー） */
    private int $createdBy;

    /** @var DateTimeInterface|null 有効期限 */
    private ?DateTimeInterface $expiredAt;

    /** @var bool 使用済みフラグ */
    private bool $isUsed;

    /** @var DateTimeInterface 作成日時 */
    private DateTimeInterface $createdAt;

    /** @var DateTimeInterface 更新日時 */
    private DateTimeInterface $updatedAt;

    /**
     * コンストラクタ
     *
     * @param int|null $id
     * @param string $ulid
     * @param int $applyTypeId
     * @param int $createdBy
     * @param DateTimeInterface|null $expiredAt
     * @param bool $isUsed
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     */
    public function __construct(
        ?int $id,
        string $ulid,
        int $applyTypeId,
        int $createdBy,
        ?DateTimeInterface $expiredAt,
        bool $isUsed,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->ulid = $ulid;
        $this->applyTypeId = $applyTypeId;
        $this->createdBy = $createdBy;
        $this->expiredAt = $expiredAt;
        $this->isUsed = $isUsed;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * 新規のスキップURL作成
     *
     * @param string $ulid
     * @param int $applyTypeId
     * @param int $createdBy
     * @param DateTimeInterface|null $expiredAt
     * @return self
     */
    public static function create(
        string $ulid,
        int $applyTypeId,
        int $createdBy,
        ?DateTimeInterface $expiredAt = null
    ): self {
        return new self(
            null,
            $ulid,
            $applyTypeId,
            $createdBy,
            $expiredAt ?? Carbon::now()->addDays(14),
            false,
            Carbon::now(),
            Carbon::now()
        );
    }

    /**
     * IDを取得
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * ULIDを取得
     *
     * @return string
     */
    public function getUlid(): string
    {
        return $this->ulid;
    }

    /**
     * 申出種別IDを取得
     *
     * @return int
     */
    public function getApplyTypeId(): int
    {
        return $this->applyTypeId;
    }

    /**
     * 作成者IDを取得
     *
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    /**
     * 有効期限を取得
     *
     * @return DateTimeInterface|null
     */
    public function getExpiredAt(): ?DateTimeInterface
    {
        return $this->expiredAt;
    }

    /**
     * 使用済みフラグを取得
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    /**
     * 作成日時を取得
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * 更新日時を取得
     *
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * 使用済みにする
     *
     * @return void
     */
    public function markAsUsed(): void
    {
        $this->isUsed = true;
        $this->updatedAt = Carbon::now();
    }

    /**
     * URLが有効かどうか確認
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->isUsed) {
            return false;
        }

        if ($this->expiredAt && Carbon::now()->isAfter($this->expiredAt)) {
            return false;
        }

        return true;
    }
}
