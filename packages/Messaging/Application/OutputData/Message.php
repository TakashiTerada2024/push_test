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

namespace Ncc01\Messaging\Application\OutputData;

use Carbon\Carbon;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Ncc01\User\Enterprise\AllSecretariatUser;

/**
 * Message
 *
 * @package Ncc01\Messaging\Application\Output
 */
class Message
{
    /** @var string $body */
    private $body;
    /** @var int $fromId */
    private $fromId;
    /** @var string $fromName */
    private $fromName;
    /** @var Carbon */
    private $createdAt;
    /** @var Carbon */
    private $updatedAt;
    /** @var string $id */
    private $id;
    /** @var int $applyId */
    private $applyId;
    /** @var Carbon $lastUpdatedAt */
    private $lastUpdatedAt;
    /** @var string|null $parentId */
    private $parentId;

    private AllSecretariatUser $allSecretariatUser;

    /**
     * Message constructor.
     * @param string $body
     * @param int $fromId
     * @param string $fromName
     * @param Carbon $createdAt
     * @param Carbon $updatedAt
     * @param string $id
     * @param int $applyId
     * @param string|null $parentId
     */
    public function __construct(
        string $body,
        int $fromId,
        string $fromName,
        Carbon $createdAt,
        Carbon $updatedAt,
        string $id,
        int $applyId,
        ?string $parentId,
    ) {
        $this->body = $body;
        $this->fromId = $fromId;
        $this->fromName = $fromName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->lastUpdatedAt = $createdAt;
        $this->id = $id;
        $this->applyId = $applyId;
        $this->parentId = $parentId;

        $this->allSecretariatUser = new AllSecretariatUser();
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getFromId(): int
    {
        return $this->fromId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getApplyId(): int
    {
        return $this->applyId;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * getFromName
     * 送信者名についての仕様について
     * @see https://github.com/git-balocco/ncc01/issues/12
     *
     * @param AuthenticatedUserInterface $authenticatedUser
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getFromName(AuthenticatedUserInterface $authenticatedUser): string
    {
        if ($authenticatedUser->isApplicant() && $this->isSentBySecretariat()) {
            return $this->allSecretariatUser->getName();
        }
        return $this->fromName;
    }

    public function isSentBySecretariat(): bool
    {
        return ($this->getFromId() === $this->allSecretariatUser->getId());
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }
    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * getLastUpdatedAt
     * 更新メッセージがある場合、更新メッセージのUpdatedAt
     * @return Carbon
     */
    public function getLastUpdatedAt(): Carbon
    {
        return $this->lastUpdatedAt;
    }
    /**
     * @param Carbon $lastUpdatedAt
     */
    public function setLastUpdatedAt(Carbon $lastUpdatedAt)
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    public function isSentByLoginUser(AuthenticatedUserInterface $authenticatedUser): bool
    {
        //1. ログイン者が事務局権限の場合。このメッセージの送信者が、特殊なアカウント（ID:2で固定）であるかを判定。
        if ($authenticatedUser->isSecretariat() && $this->isSentBySecretariat()) {
            return true;
        }
        //2.ログイン者が申出者権限の場合。このメッセージの送信者と一致しているかを判定。
        if ($authenticatedUser->isApplicant() && $this->getFromId() === $authenticatedUser->getId()) {
            return true;
        }

        return false;
    }

    /**
     * canEditUser
     *
     * @param AuthenticatedUserInterface $authenticatedUser
     * @return bool
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function canEditUser(AuthenticatedUserInterface $authenticatedUser): bool
    {
        if (
            ($authenticatedUser->isSuperAdmin() || $authenticatedUser->isSecretariat())
            && $this->isSentBySecretariat()
            && $this->canEdit()
        ) {
            return true;
        }
        return false;
    }

    /**
     * isDeleted
     *
     * @return bool
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function isDeleted(): bool
    {
        //簡易的に、内容の文字列で削除済みかどうかを判定する。
        //（削除フラグを設けるまでもないと判断）
        return trim($this->getBody()) == __('apply.message.deleted');
    }

    /**
     * canEdit
     *
     * @return bool
     * @author ushiro <k.ushiro@balocco.info>
     */
    protected function canEdit(): bool
    {
        if ($this->getParentId() || $this->isDeleted()) {
            return false;
        }

        return true;
    }
}
