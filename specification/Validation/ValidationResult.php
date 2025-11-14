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

namespace Specification\Validation;

use ArrayIterator;
use Specification\Validation\Contracts\ValidationResultInterface;

/**
 * ValidationResult
 *
 * @package Specification\Validation
 */
class ValidationResult implements ValidationResultInterface
{
    private bool $isValid;
    private string $specKey;
    private string $specName;
    private array $messages;
    private string $message;

    /**
     * @param bool $isValid
     * @param string $specKey
     * @param string $specName
     * @param array $messages
     * @param string $message
     */
    public function __construct(bool $isValid, string $specKey, string $specName, array $messages, string $message)
    {
        $this->isValid = $isValid;
        $this->specKey = $specKey;
        $this->specName = $specName;
        $this->messages = $messages;
        $this->message = $message;
    }


    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return string
     */
    public function getSpecKey(): string
    {
        return $this->specKey;
    }

    /**
     * @return string
     */
    public function getSpecName(): string
    {
        return $this->specName;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function get(string ...$keys): ValidationResultInterface
    {
        return $this;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator([$this]);
    }


}
