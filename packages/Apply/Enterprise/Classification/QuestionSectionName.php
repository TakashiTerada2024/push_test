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

namespace Ncc01\Apply\Enterprise\Classification;

use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\KeyValueList;

/**
 * QuestionItemName
 * 質問項目名称表
 * @package Ncc01\Apply\Enterprise\Classification
 */
class QuestionSectionName extends KeyValueList
{
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                1 => '1.申出に係る情報の名称',
                2 => '2.情報の利用目的',
                3 => '3.提供依頼申出者及び利用者',
                4 => '4.利用する情報の範囲',
                5 => '5.利用する登録情報及び調査研究方法',
                6 => '6.利用期間',
                7 => '7.利用場所、利用する環境、保管場所及び管理方法',
                8 => '8.調査研究成果の公表方法及び公表予定時期',
                9 => '9.情報等の利用後の処置',
                10 => '10.その他'
            ]
        );
    }
}
