<?php

namespace Ncc01\Apply\Enterprise\Classification;

use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\KeyValueList;

/**
 * ScreenLocks
 * 画面ロックの種類を定義するクラス
 * @package Ncc01\Apply\Enterprise\Classification
 */
class ScreenLocks extends KeyValueList
{
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                'basic' => '基本情報',
                'section1' => '1. 提供を求める情報の内容',
                'section2' => '2. 提供を求める情報の取扱い',
                'section3' => '3. 提供を求める情報の利用',
                'section4' => '4. 安全管理措置',
                'section5' => '5. 情報の提供の方法',
                'section6' => '6. 情報の利用期間',
                'section7' => '7. 利用目的の達成により不要となった情報の取扱い',
                'section8' => '8. 提供を求める情報に係る本人の同意',
                'section9' => '9. 事業所管大臣の確認',
                'section10' => '10. その他',
                'attachment' => '添付資料画面',
            ]
        );
    }
}
