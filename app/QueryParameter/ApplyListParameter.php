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

namespace App\QueryParameter;

use Ncc01\Apply\Application\QueryParameterInterface\ApplyListParameterInterface;

/**
 * ApplyListParameter
 *
 * @package App\QueryParameter
 */
class ApplyListParameter implements ApplyListParameterInterface
{
    /** @var array|null */
    private $status;

    /** @var bool|null */
    private $isRepliedBySecretariat;
    /** @var array|null */
    private $type;
    /** @var bool|null */
    private $isShowAccepted;

    /**
     * @return array|null
     */
    public function getType(): ?array
    {
        return $this->type;
    }

    /**
     * @param array $type
     */
    public function setType(array $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array|null
     */
    public function getStatus(): ?array
    {
        return $this->status;
    }

    /**
     * @param array $status
     */
    public function setStatus(array $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool|null
     */
    public function getIsRepliedBySecretariat(): ?bool
    {
        return $this->isRepliedBySecretariat;
    }

    /**
     * @param bool $isRepliedBySecretariat
     */
    public function setIsRepliedBySecretariat(bool $isRepliedBySecretariat): void
    {
        $this->isRepliedBySecretariat = $isRepliedBySecretariat;
    }

    /**
     * getIsShowAccepted
     *
     * @return bool|null
     * @author anhpd
     */
    public function getIsShowAccepted(): ?bool
    {
        return $this->isShowAccepted;
    }

    /**
     * setIsShowAccepted
     *
     * @param $isShowAccepted
     * @return void
     * @author anhpd
     */
    public function setIsShowAccepted(bool $isShowAccepted): void
    {
        $this->isShowAccepted = $isShowAccepted;
    }
}
