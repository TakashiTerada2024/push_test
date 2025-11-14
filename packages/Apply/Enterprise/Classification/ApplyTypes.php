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

use GitBalocco\KeyValueList\Classification;
use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Contracts\KeyValueListable;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use Ncc01\Apply\Enterprise\Entity\ApplyType;
use Ncc01\Apply\Enterprise\Gateway\ApplyTypeViewPathInterface;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\CheckingDocumentValidationSpec;

/**
 * ApplyTypes
 *
 * @package Ncc01\Apply\Enterprise\Classification
 */
class ApplyTypes extends Classification
{
    /** @var int GOVERNMENT_LINKAGE */
    public const GOVERNMENT_LINKAGE = 1;
    /** @var int GOVERNMENT_STATISTICS */
    public const GOVERNMENT_STATISTICS = 2;
    /** @var int CIVILIAN_LINKAGE */
    public const CIVILIAN_LINKAGE = 3;
    /** @var int CIVILIAN_STATISTICS */
    public const CIVILIAN_STATISTICS = 4;

    /**
     * getDefiner
     *
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                [
                    'type' => self::GOVERNMENT_LINKAGE,
                    'name' => '行政関係者・リンケージ利用',
                ],
                [
                    'type' => self::GOVERNMENT_STATISTICS,
                    'name' => '行政関係者・集計統計利用',
                ],
                [
                    'type' => self::CIVILIAN_LINKAGE,
                    'name' => '研究者等・リンケージ利用',
                ],
                [
                    'type' => self::CIVILIAN_STATISTICS,
                    'name' => '研究者等・集計統計利用',
                ],
            ]
        );
    }

    /**
     * valueOfName
     *
     * @param int|null $applyType
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function valueOfName(?int $applyType): string
    {
        if (is_null($applyType)) {
            return '';
        }
        return $this->valueOf('name', $applyType);
    }

    /**
     * listOfName
     *
     * @return KeyValueListable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function listOfName(): KeyValueListable
    {
        return $this->listOf('name');
    }

    /**
     * listOfType
     *
     * @return KeyValueListable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function listOfType(): KeyValueListable
    {
        return $this->listOf('type');
    }

    /**
     * getIdentityIndex
     *
     * @return int|string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function getIdentityIndex()
    {
        return 'type';
    }
}
