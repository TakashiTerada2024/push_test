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

namespace Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section03;

use Carbon\Carbon;
use Specification\Validation\ValidationSpecCollection;

/**
 * Section03ValidationSpec
 *
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument2\Items\Section03
 */
class Section03ValidationSpec extends ValidationSpecCollection
{
    public function __construct(
        private ?int $applyTypeId,
        private ?int $attachment301Id,
        private ?int $attachment302Id,
        private array $applyUsers,
        private ?int $numberOfUsers,
        private ?string $applicantName,
        private ?string $applicantNameKana,
        private ?int $applicantType,
        private ?Carbon $applicantBirthday,
        private ?string $applicantAddress,
        private ?string $affiliation
    ) {
    }

    public function getSpecKey(): string
    {
        return 'section03';
    }

    public function getSpecName(): string
    {
        return '申出項目03';
    }

    protected function initCollection()
    {
        $this->add(new ApplyUsersSpec($this->applyUsers, $this->numberOfUsers));
        $this->add(new Attachment301Spec($this->attachment301Id, $this->applyTypeId));
        $this->add(new Attachment302Spec($this->attachment302Id, $this->applyTypeId));

        $this->add(new ApplicantAddressSpec($this->applicantAddress, $this->applicantType));
        $this->add(new ApplicantBirthdaySpec($this->applicantBirthday, $this->applicantType));
//        $this->add(new ApplicantNameKanaSpec($this->applicantNameKana));
        $this->add(new ApplicantNameSpec($this->applicantName, $this->applicantType));
        $this->add(new ApplicantTypeSpec($this->applicantType));

        $this->add(new AffiliationSpec($this->affiliation, $this->applicantType));
    }
}
