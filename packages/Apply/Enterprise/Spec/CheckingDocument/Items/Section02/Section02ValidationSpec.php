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

use Carbon\Carbon;
use Specification\Validation\ValidationSpecCollection;

/**
 * Section02ValidationSpec
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) これ以上減らすことはできない
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument2\Items\Section02
 */
class Section02ValidationSpec extends ValidationSpecCollection
{
    public function __construct(
        private ?string $purposeOfUse,
        private ?string $needToUse,
        private ?int $applyTypeId,
        private ?int $ethicalReviewStatus,
        private ?string $ethicalReviewRemark,
        private ?string $ethicalReviewBoardName,
        private ?string $ethicalReviewBoardCode,
        private ?Carbon $ethicalReviewBoardDate,
        private ?int $attachment201Id,
        private ?int $attachment202Id,
        private ?int $attachment203Id,
        private ?int $attachment204Id,
        private ?int $attachment205Id,
    ) {
    }

    public function getSpecKey(): string
    {
        return 'section02';
    }

    public function getSpecName(): string
    {
        return '申出項目02';
    }

    protected function initCollection()
    {
        $this->add(new Attachment201Spec($this->attachment201Id, $this->applyTypeId));
        $this->add(new Attachment202Spec($this->attachment202Id, $this->applyTypeId));
        $this->add(new Attachment203Spec($this->attachment203Id, $this->applyTypeId));
        $this->add(new Attachment204Spec($this->attachment204Id, $this->applyTypeId));
        $this->add(new NeedToUseSpec($this->needToUse));
        $this->add(new PurposeOfUseSpec($this->purposeOfUse));

        //倫理審査
        //$this->add(new EthicalReviewStatusSpec($this->ethicalReviewStatus));
        $this->add(new EthicalReviewRemarkSpec($this->ethicalReviewRemark, $this->ethicalReviewStatus));
        $this->add(new EthicalReviewBoardNameSpec($this->ethicalReviewBoardName, $this->ethicalReviewStatus));
        $this->add(new EthicalReviewBoardCodeSpec($this->ethicalReviewBoardCode, $this->ethicalReviewStatus));
        $this->add(new EthicalReviewBoardDateSpec($this->ethicalReviewBoardDate, $this->ethicalReviewStatus));
        $this->add(new Attachment205Spec($this->attachment205Id, $this->applyTypeId, $this->ethicalReviewStatus));
    }
}
