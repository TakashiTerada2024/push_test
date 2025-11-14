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

namespace Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section04;

use Ncc01\Apply\Enterprise\Classification\QuestionItemName;
use Ncc01\Apply\Enterprise\Classification\YearsOfDiagnose;
use Respect\Validation\Validator;
use Specification\Validation\ValidationSpec;

/**
 * YearOfDiagnoseStartSpec
 *
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument2\Items\Section04
 */
class YearOfDiagnoseStartSpec extends ValidationSpec
{
    private YearsOfDiagnose $yearsOfDiagnoseList;

    public function __construct(mixed $candidate, private ?int $yearOfDiagnoseEnd)
    {
        parent::__construct($candidate);
        $this->yearsOfDiagnoseList = new YearsOfDiagnose();
    }

    /**
     * definition
     *
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) RespectValidationのStaticAccess許可
     */
    public function definition(): mixed
    {
        $validator = Validator::notEmpty()->intType()->in($this->yearsOfDiagnoseList->keys());
        if ($this->getCandidate() && $this->yearOfDiagnoseEnd) {
            //終了年次が決定している場合は、終了年次以下の数値であることを検証
            $validator->oneOf(
                Validator::equivalent($this->yearOfDiagnoseEnd),
                Validator::lessThan($this->yearOfDiagnoseEnd)
            );
        }
        return $validator;
    }

    public function getSpecKey(): string
    {
        return '4_year_of_diagnose_start';
    }

    public function getSpecName(): string
    {
        return (new QuestionItemName())->value('4_year_of_diagnose') . '（始期）';
    }
}
