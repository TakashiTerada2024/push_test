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

namespace Ncc01\Common\Enterprise\Classification;

use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\LaravelCacheKeyValueList;

/**
 * Prefectures
 * 都道府県コード
 * @package Ncc01\Common\Enterprise\Classification
 */
class Prefectures extends LaravelCacheKeyValueList
{
    /**
     * getDefiner
     *
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                1 => '北海道',
                2 => '青森県',
                3 => '岩手県',
                4 => '宮城県',
                5 => '秋田県',
                6 => '山形県',
                7 => '福島県',
                8 => '茨城県',
                9 => '栃木県',
                10 => '群馬県',
                11 => '埼玉県',
                12 => '千葉県',
                13 => '東京都',
                14 => '神奈川県',
                15 => '新潟県',
                16 => '富山県',
                17 => '石川県',
                18 => '福井県',
                19 => '山梨県',
                20 => '長野県',
                21 => '岐阜県',
                22 => '静岡県',
                23 => '愛知県',
                24 => '三重県',
                25 => '滋賀県',
                26 => '京都府',
                27 => '大阪府',
                28 => '兵庫県',
                29 => '奈良県',
                30 => '和歌山県',
                31 => '鳥取県',
                32 => '島根県',
                33 => '岡山県',
                34 => '広島県',
                35 => '山口県',
                36 => '徳島県',
                37 => '香川県',
                38 => '愛媛県',
                39 => '高知県',
                40 => '福岡県',
                41 => '佐賀県',
                42 => '長崎県',
                43 => '熊本県',
                44 => '大分県',
                45 => '宮崎県',
                46 => '鹿児島県',
                47 => '沖縄県',
            ]
        );
    }
}
