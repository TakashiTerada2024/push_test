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

namespace Ncc01\Attachment\Application\InputData;

use LogicException;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveAttachmentParameter
 *
 * @package Ncc01\Attachment\Application\Usecase
 */
class SaveAttachmentParameter implements SaveAttachmentParameterInterface
{
    /** @var int|null $id */
    private $id;
    /** @var int $userId */
    private $userId;
    /** @var int $applyId */
    private $applyId;
    /** @var int|null $attachmentTypeId */
    private $attachmentTypeId;
    /** @var string $content */
    private $content;
    /** @var string $clientOriginalName */
    private $clientOriginalName;
    /** @var int $status */
    private $status = AttachmentStatuses::UPLOADED;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * getUserId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getUserId(): int
    {
        if (is_null($this->userId)) {
            throw new LogicException('userId is required.');
        }
        return $this->userId;
    }

    /**
     * setUserId
     *
     * @param int $userId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * getApplyId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getApplyId(): int
    {
        if (is_null($this->userId)) {
            throw new LogicException('applyId is required.');
        }
        return $this->applyId;
    }

    /**
     * setApplyId
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setApplyId(int $applyId): void
    {
        $this->applyId = $applyId;
    }

    /**
     * getAttachmentTypeId
     *
     * @return int|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getAttachmentTypeId(): ?int
    {
        return $this->attachmentTypeId;
    }

    /**
     * setAttachmentTypeId
     *
     * @param int|null $attachmentTypeId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAttachmentTypeId(?int $attachmentTypeId): void
    {
        $this->attachmentTypeId = $attachmentTypeId;
    }

    /**
     * getContent
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getContent(): string
    {
        if (is_null($this->userId)) {
            throw new LogicException('content is required.');
        }

        return $this->content;
    }

    /**
     * setContent
     *
     * @param string $content
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * getClientOriginalName
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getClientOriginalName(): string
    {
        if (is_null($this->userId)) {
            throw new LogicException('clientOriginalName is required.');
        }

        return $this->clientOriginalName;
    }

    /**
     * setClientOriginalName
     *
     * @param string $clientOriginalName
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setClientOriginalName(string $clientOriginalName): void
    {
        $this->clientOriginalName = $clientOriginalName;
    }

    /**
     * getStatus
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * setStatus
     *
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
