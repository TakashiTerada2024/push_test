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

namespace App\Common;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * ReadOnlyNullableArray
 *
 * @package App\Common
 */
class ReadOnlyNullableArray implements ArrayAccess, IteratorAggregate
{
    private $data;

    /**
     * ReadOnlyNullableArray constructor.
     * @param $data
     */
    public function __construct(?array $data)
    {
        $this->data = $data;
    }

    /**
     * offsetExists
     *
     * @param mixed $offset
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function offsetExists($offset): bool
    {
        return is_array($this->data) && array_key_exists($offset, $this->data);
    }

    /**
     * offsetGet
     *
     * @param mixed $offset
     * @return mixed|string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function offsetGet($offset): mixed
    {
        if (!is_array($this->data)) {
            return new ReadOnlyNullableArray(null);
        }

        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return new ReadOnlyNullableArray(null);
    }

    /**
     * offsetSet
     *
     * @param mixed $offset
     * @param mixed $value
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function offsetSet($offset, $value): void
    {
        throw new LogicException(
            'this is readonly object. You should not set value. $offset:' . $offset . ' $value :' . $value
        );
    }

    /**
     * offsetUnset
     *
     * @param mixed $offset
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @throws LogicException
     */
    public function offsetUnset($offset): void
    {
        throw new LogicException('this is readonly object. You should not unset. $offset:' . $offset);
    }

    /**
     * getIterator
     *
     * @return ArrayIterator|Traversable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getIterator(): Traversable
    {
        $array = $this->data;
        if (!is_array($array)) {
            $array = [];
        }

        return new ArrayIterator($array);
    }

    /**
     * __toString
     *
     * @return false|string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __toString()
    {
        if ($this->data) {
            return json_encode($this->data);
        }
        return '';
    }
}
