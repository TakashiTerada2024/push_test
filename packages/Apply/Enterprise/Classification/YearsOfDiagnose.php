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

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\KeyValueList;

/**
 * YearOfDiagnose
 * 「診断年次」の選択肢に関する定義。
 * 毎年10月1日を区切りとし、
 * 10月1日以前は、2016年次から「現在年から3年前の年次」までを選択可能
 * 10月1日以降は、2016年次から「現在年から2年前の年次」までを選択可能　
 * とする。
 * @package Ncc01\Apply\Enterprise\Classification
 */
class YearsOfDiagnose extends KeyValueList
{
    /**
     * getDefiner
     *
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getDefiner(): Definer
    {
        $define = [];
        $period = $this->createPeriod();
        /** @var Carbon $carbon */
        foreach ($period as $carbon) {
            $define[$carbon->year] = (string)$carbon->year . '年次';
        }
        return new ArrayDefiner($define);
    }

    /**
     * createPeriod
     *
     * @return CarbonPeriod
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @psalm-suppress UndefinedMagicMethod Carbon側が原因で生じる警告のため無視
     */
    private function createPeriod(): CarbonPeriod
    {
        return CarbonPeriod::create($this->start(), $this->end())->years();
    }

    /**
     * start
     * 選択肢の開始点の定義
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function start(): string
    {
        return '2016-01-01 00:00:00';
    }

    /**
     * end
     * 選択肢の終点の定義
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function end(): string
    {
        $now = new Carbon();
        if ($now->month < 10) {
            //10月1日より前は、2016年次から「現在年から3年前の年次」までを選択可能
            return $now->subYear(3)->firstOfYear()->toDateTimeString();
        }
        //10月1日以後は、2016年次から「現在年から2年前の年次」までを選択可能　
        return $now->subYear(2)->firstOfYear()->toDateTimeString();
    }
}
