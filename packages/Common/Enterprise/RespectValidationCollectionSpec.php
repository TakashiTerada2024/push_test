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

namespace Ncc01\Common\Enterprise;

/**
 * RepectValidationCollectionSpec
 *
 * @package Ncc01\Common\Enterprise
 */
abstract class RespectValidationCollectionSpec implements ValidationSpecInterface
{
    private array $message = [];
    private array $messages = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * isSatisfied
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSatisfied(): bool
    {
        $collection = $this->createValidationCollection();
        $result = $collection->isSatisfied();

        $this->setMessages($collection->getMessages());
        $this->setMessage($collection->getMessage());
        return $result;
    }

    /**
     * createValidationCollection
     *
     * @return RespectValidationCollection
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    abstract protected function createValidationCollection(): RespectValidationCollection;

    private function setMessages(array $messages): void
    {
        if (count($messages) > 0) {
            $this->messages = $messages;
        }
    }

    private function setMessage(array $message): void
    {
        if (count($message) > 0) {
            $this->message = $message;
        }
    }
}
