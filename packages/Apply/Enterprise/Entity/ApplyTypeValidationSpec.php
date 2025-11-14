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

use Ncc01\Apply\Enterprise\Classification\ApplyTypes;
use Ncc01\Common\Enterprise\ValidationSpec;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

/**
 * ApplyTypeValidationSpec
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class ApplyTypeValidationSpec extends ValidationSpec
{
    /** @var ApplyTypes $applyTypes */
    private $applyTypes;

    /**
     * ApplyTypeValidationSpec constructor.
     * @param $candidate
     */
    public function __construct($candidate)
    {
        parent::__construct($candidate);
        $this->applyTypes = new ApplyTypes();
    }


    /**
     * isSatisfied
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSatisfied(): bool
    {
        $candidate = $this->getCandidate();
        try {
            //ApplyTypeが満たすべき仕様を定義する
            V::intType()->in($this->applyTypes->listOfType()->all())->assert($candidate);
            $result = true;
        } catch (NestedValidationException $exception) {
            $this->setMessages($exception->getMessages());
            $result = false;
        }
        return $result;
    }

    public function getItemKey(): string
    {
        return 'applyType';
    }

    public function getItemName(): string
    {
        return '申出種別';
    }
}
