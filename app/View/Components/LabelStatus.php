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

namespace App\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * LabelStatus
 *
 * @package App\View\Components
 */
class LabelStatus extends Component
{
    /** @var int $statusId */
    public $statusId;
    /** @var int destinationApplyId */
    public $destinationApplyId;
    /** @var ApplyStatuses $applyStatuses */
    private $applyStatuses;

    /**
     * @var string[] $classNames
     */
    private $classNames = [
        1 => 'bg-base-700',
        2 => 'bg-main-300',
        3 => 'bg-main-500',
        4 => 'bg-main-700',
        5 => 'bg-accent-500',
        6 => 'bg-accent-700',
        20 => 'bg-accepted-300'
    ];

    public function __construct(ApplyStatuses $applyStatuses, int $statusId, int $destinationApplyId = 0)
    {
        $this->applyStatuses = $applyStatuses;
        $this->statusId = $statusId;
        $this->destinationApplyId = $destinationApplyId;
    }

    public function statusName()
    {
        return $this->applyStatuses->value($this->statusId) ?: 'ステータス不明';
    }

    public function classNames()
    {
        if ($this->statusId == ApplyStatuses::ACCEPTED && !empty($this->destinationApplyId)) {
            if ($this->applyStatuses->getApplyStatusById($this->destinationApplyId) == ApplyStatuses::ACCEPTED) {
                return "bg-copied-apply-green";
            }
            return "bg-copied-apply-yellow";
        }
        if (array_key_exists($this->statusId, $this->classNames)) {
            return $this->classNames[$this->statusId];
        }
        return 'bg-gray-200';
    }

    public function render()
    {
        return View::make('components.label-status');
    }
}
