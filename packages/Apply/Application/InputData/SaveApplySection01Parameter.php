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

namespace Ncc01\Apply\Application\InputData;

use Ncc01\Apply\Application\InputBoundary\SaveApplySection01ParameterInterface;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveApplyParameter
 *
 * @package Ncc01\Apply\Application\Input
 */
class SaveApplySection01Parameter implements SaveApplySection01ParameterInterface
{
    /** @var SaveAttachmentParameterInterface|null $attachment101 添付ファイル101の保存内容を示すパラメータ */
    private $attachment101;
    /** @var SaveAttachmentParameterInterface|null $attachment102 添付ファイル102の保存内容を示すパラメータ */
    private $attachment102;
    /** @var SaveAttachmentParameterInterface|null $attachment103 添付ファイル103の保存内容を示すパラメータ */
    private $attachment103;

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment101(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment101;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment101
     */
    public function setAttachment101(?SaveAttachmentParameterInterface $attachment101): void
    {
        $this->attachment101 = $attachment101;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment102(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment102;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment102
     */
    public function setAttachment102(?SaveAttachmentParameterInterface $attachment102): void
    {
        $this->attachment102 = $attachment102;
    }

    /**
     * @return SaveAttachmentParameterInterface|null
     */
    public function getAttachment103(): ?SaveAttachmentParameterInterface
    {
        return $this->attachment103;
    }

    /**
     * @param SaveAttachmentParameterInterface|null $attachment103
     */
    public function setAttachment103(?SaveAttachmentParameterInterface $attachment103): void
    {
        $this->attachment103 = $attachment103;
    }
}
