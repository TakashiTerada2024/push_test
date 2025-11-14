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
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Common\Enterprise\IntValue;

/**
 * ApplyStatus
 *
 * @package Ncc01\Apply\Enterprise\Entity
 */
class ApplyStatus extends IntValue
{
    /** @var ApplyStatuses $applyStatuses */
    private $applyStatuses;

    /**
     * ApplyStatus constructor.
     * @param int $value
     * @throws LogicException
     */
    public function __construct(int $value)
    {
        $spec = new ApplyStatusValidationSpec($value);
        if (!$spec->isSatisfied()) {
            throw new LogicException('Invalid ApplyStatus. ' . implode(',', $spec->getMessages()));
        }
        $this->value = $value;
        $this->applyStatuses = new ApplyStatuses();
    }

    /**
     * getName
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getName(): string
    {
        return $this->applyStatuses->value($this->getValue());
    }

    /**
     * isCreatingDocument
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isCreatingDocument(): bool
    {
        return ($this->value === ApplyStatuses::CREATING_DOCUMENT);
    }

    /**
     * isCheckingDocument
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isCheckingDocument(): bool
    {
        return ($this->value === ApplyStatuses::CHECKING_DOCUMENT);
    }

    /**
     * isCancel
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isCancel(): bool
    {
        return ($this->value === ApplyStatuses::CANCEL);
    }

    /**
     * isAccepted
     *
     * @return bool
     * @author khoipham <phamkykhoi.info@gmail.>
     */
    public function isAccepted(): bool
    {
        return $this->value === ApplyStatuses::ACCEPTED;
    }

    /**
     * isPriorConsultation
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isPriorConsultation(): bool
    {
        return ($this->value === ApplyStatuses::PRIOR_CONSULTATION);
    }

    public function isSubmittingDocument(): bool
    {
        return ($this->value === ApplyStatuses::SUBMITTING_DOCUMENT);
    }
}
