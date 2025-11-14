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

namespace Specification\Validation\Examples;

use Specification\Validation\ValidationSpecCollection;

/**
 * ExampleSpecCollection02
 * 二重の構造のサンプル
 *
 * @package Specification\Validation\Examples
 */
class ExampleSpecCollection02 extends ValidationSpecCollection
{
    protected array $customMessages = [
        'notEmpty' => 'ほげーこれくしょん2'
    ];

    //以下、サブクラスに実装を任せる予定のメソッド
    public function __construct($can1, $can2, $can3, $can4)
    {
        $this->can1 = $can1;
        $this->can2 = $can2;
        $this->can3 = $can3;
        $this->can4 = $can4;
    }

    public function definition(): mixed
    {
        return [
            new ExampleSpec01($this->can1),
            new ExampleSpec02($this->can2),
            new ExampleSpecCollection01($this->can3, $this->can4),
        ];
    }

    public function getSpecKey(): string
    {
        return 'double-group-sample';
    }

    public function getSpecName(): string
    {
        return 'スペックの集合2';
    }
}
