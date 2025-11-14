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
 * ValidationResultCollection
 *
 * @package Specification\Validation
 */
class ValidationResultCollection implements ValidationResultInterface
{
    private string $specKey;
    private string $specName;
    private array $collection = [];
    private bool $isValid = true;

    /**
     * @param string $specKey
     * @param string $specName
     */
    public function __construct(string $specKey, string $specName)
    {
        $this->specKey = $specKey;
        $this->specName = $specName;
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

    public function getMessages(): array
    {
        $messages = [];
        /** @var ValidationResultInterface $result */
        foreach ($this->collection as $key => $result) {
            $messages[$key] = $result->getMessages();
        }
        return $messages;
    }

    //ココから下は、コレクションをなんとかする処理を実装するはず・・・

    public function getMessage(): string
    {
        $tmp = [];
        /** @var ValidationResultInterface $result */
        foreach ($this->collection as $key => $result) {
            $tmp[$key] = $result->getMessage();
        }
        return '未実装';
    }

    public function add(string $key, ValidationResultInterface $result)
    {
        $this->collection[$key] = $result;
        // 追加の場合、全部の再計算は無駄なのでrefreshIsValid() はしてません。
        // 現在の状態と追加する結果の論理積を格納する。
        $this->isValid = ($this->isValid && $result->isValid());
    }

    public function remove(string $key): void
    {
        if (array_key_exists($key, $this->collection)) {
            $this->collection[$key] = null;
            $this->refreshIsValid();
        }
    }

    /**
     * get
     *
     * @param string ...$keys
     * @return ValidationResultInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function get(string ...$keys): ValidationResultInterface
    {
        if (count($keys) === 0) {
            return $this;
        }
        $key = array_shift($keys);
        return ($this->collection[$key] ?? (new NullValidationResult))->get(...$keys);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->collection);
    }

    private function refreshIsValid(): void
    {
        //初期化
        $tmpIsValid = true;

        /** @var ValidationResultInterface $result */
        foreach ($this->collection as $result) {
            //コレクション内のバリデーション結果が全てtrueなら、true
            $tmpIsValid = ($tmpIsValid && $result->isValid());
        }
        $this->isValid = $tmpIsValid;
    }


}
