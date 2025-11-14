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

namespace Ncc01\User\Enterprise;

/**
 * Role
 *
 * @package Ncc01\User\Enterprise
 */
class Role
{
    /** スーパー管理者ロールID */
    public const SUPER_ADMIN_ROLE_ID = 1;
    
    /** 事務局ロールID */
    public const SECRETARIAT_ROLE_ID = 2;
    
    /** 申出者ロールID */
    public const APPLICANT_ROLE_ID = 3;

    /** @var int $value */
    private $value;

    /**
     * Role constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->value = $id;
    }

    /**
     * __toString
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * isSuperAdmin
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSuperAdmin(): bool
    {
        return ($this->getValue() === self::SUPER_ADMIN_ROLE_ID);
    }

    /**
     * getValue
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * isSecretariat
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSecretariat(): bool
    {
        return ($this->getValue() === self::SECRETARIAT_ROLE_ID);
    }

    /**
     * isApplicant
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isApplicant(): bool
    {
        return ($this->getValue() === self::APPLICANT_ROLE_ID);
    }
}
