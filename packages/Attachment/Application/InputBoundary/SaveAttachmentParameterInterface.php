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

namespace Ncc01\Attachment\Application\InputBoundary;

/**
 * SaveAttachmentParameterInterface
 *
 * @package Ncc01\Attachment\Application\Gateway
 */
interface SaveAttachmentParameterInterface
{
    /**
     * getId
     *
     * @return int|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getId(): ?int;

    /**
     * setId
     *
     * @param int $id
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setId(int $id): void;

    /**
     * getUserId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getUserId(): int;

    /**
     * setUserId
     *
     * @param int $userId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setUserId(int $userId): void;

    /**
     * getApplyId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getApplyId(): int;

    /**
     * setApplyId
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setApplyId(int $applyId): void;

    /**
     * getAttachmentTypeId
     *
     * @return int|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getAttachmentTypeId(): ?int;

    /**
     * setAttachmentTypeId
     *
     * @param int|null $attachmentTypeId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAttachmentTypeId(?int $attachmentTypeId): void;

    /**
     * getContent
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getContent(): string;

    /**
     * setContent
     *
     * @param string $content
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setContent(string $content): void;

    /**
     * getClientOriginalName
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getClientOriginalName(): string;

    /**
     * setClientOriginalName
     *
     * @param string $clientOriginalName
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setClientOriginalName(string $clientOriginalName): void;

    /**
     * getStatus
     *
     * @return int
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function getStatus(): int;

    /**
     * setStatus
     *
     * @param int $status
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function setStatus(int $status): void;
}
