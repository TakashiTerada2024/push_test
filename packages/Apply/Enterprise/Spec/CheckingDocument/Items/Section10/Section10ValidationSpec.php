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

namespace Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section10;

use Specification\Validation\ValidationSpecCollection;

/**
 * Section10ValidationSpec
 *
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument2\Items\Section10
 */
class Section10ValidationSpec extends ValidationSpecCollection
{
    public function __construct(
        private ?string $clerkName,
        private ?string $clerkContactAddress,
        private ?string $clerkContactEmail,
        private ?string $clerkContactPhoneNumber
    ) {
    }

    public function getSpecKey(): string
    {
        return 'section10';
    }

    public function getSpecName(): string
    {
        return '申出項目10';
    }

    protected function initCollection()
    {
        $this->add(new ClerkNameSpec($this->clerkName));
        $this->add(new ClerkContactAddressSpec($this->clerkContactAddress));
        $this->add(new ClerkContactEmailSpec($this->clerkContactEmail));
        $this->add(new ClerkContactPhoneNumberSpec($this->clerkContactPhoneNumber));
    }
}
