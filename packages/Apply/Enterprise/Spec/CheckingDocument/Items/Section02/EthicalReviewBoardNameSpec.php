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

namespace Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section02;

use Ncc01\Apply\Enterprise\Classification\QuestionItemName;
use Respect\Validation\Validator;
use Specification\Validation\ValidationSpec;

/**
 * EthicalReviewBoardNameSpec
 *
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section02
 */
class EthicalReviewBoardNameSpec extends ValidationSpec
{
    public function __construct(mixed $candidate, private ?int $ethicalReviewStatus)
    {
        parent::__construct($candidate);
    }

    public function definition(): mixed
    {
        //「承認済み」が選択されている場合は必須
        if ($this->ethicalReviewStatus === 1) {
            return Validator::notEmpty();
        }
        return Validator::alwaysValid();
    }

    public function getSpecKey(): string
    {
        return '2_ethical_review_board_name';
    }

    public function getSpecName(): string
    {
        return (new QuestionItemName())->value('2_ethical_review_board_name');
    }
}
