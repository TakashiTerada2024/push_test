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

namespace Ncc01\Messaging\Enterprise\Entity;

/**
 * Message
 * ユーザ間（申出者、窓口組織）でやり取りされるメッセージ1通に相当するクラス
 * @package Ncc01\Messaging\Entity
 */
class Message
{
    /** @var string $body メッセージ本体 */
    private $body;
    /** @var int $fromId メッセージ送信者ID */
    private $fromId;
    /** @var string $fromName 送信者名 */
    private $fromName;

    /**
     * toArray
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function toArray(): array
    {
        return [
            'body' => $this->body,
            'fromId' => $this->fromId,
            'fromName' => $this->fromName,
        ];
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
    public function setBody(string $body): void
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
     * @param int $fromId
     */
    public function setFromId(int $fromId): void
    {
        $this->fromId = $fromId;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     */
    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }
}
