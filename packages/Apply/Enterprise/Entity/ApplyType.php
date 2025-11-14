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

namespace Ncc01\Apply\Enterprise\Entity;

use LogicException;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Ncc01\Common\Enterprise\IntValue;
use Ncc01\Common\Enterprise\ValueObjectInterface;

/**
 * ApplyType
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class ApplyType extends IntValue
{
    /** @var ApplyTypes $applyTypes */
    private $applyTypes;

    /**
     * ApplyTypeId constructor.
     * @param int $applyType
     */
    public function __construct(int $applyType)
    {
        $spec = new ApplyTypeValidationSpec($applyType);
        if (!$spec->isSatisfied()) {
            throw new LogicException('Invalid ApplyType. ' . implode(',', $spec->getMessages()));
        }
        $this->value = $applyType;

        $this->applyTypes = new ApplyTypes();
    }

    /**
     * getName
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getName(): string
    {
        return $this->applyTypes->valueOfName($this->getValue());
    }

    /**
     * getId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getId(): int
    {
        return $this->getValue();
    }

    /**
     * isLinkage
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isLinkage(): bool
    {
        return ($this->value === ApplyTypes::GOVERNMENT_LINKAGE || $this->value === ApplyTypes::CIVILIAN_LINKAGE);
    }

    /**
     * isStatistics
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isStatistics(): bool
    {
        return (
            $this->value === ApplyTypes::GOVERNMENT_STATISTICS || $this->value === ApplyTypes::CIVILIAN_STATISTICS
        );
    }
}
