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

namespace Ncc01\Apply\Application\InputBoundary;

use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * Interface SaveApplySection01ParameterInterface
 * @package Ncc01\Apply\Application\Gateway
 */
interface SaveApplySection01ParameterInterface
{
    /**
     * getAttachment101
     *
     * @return SaveAttachmentParameterInterface|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getAttachment101(): ?SaveAttachmentParameterInterface;

    /**
     * setAttachment101
     *
     * @param SaveAttachmentParameterInterface $attachment101
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAttachment101(SaveAttachmentParameterInterface $attachment101): void;

    /**
     * getAttachment102
     *
     * @return SaveAttachmentParameterInterface|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getAttachment102(): ?SaveAttachmentParameterInterface;

    /**
     * setAttachment102
     *
     * @param SaveAttachmentParameterInterface $attachment102
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAttachment102(SaveAttachmentParameterInterface $attachment102): void;

    /**
     * getAttachment103
     *
     * @return SaveAttachmentParameterInterface|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getAttachment103(): ?SaveAttachmentParameterInterface;

    /**
     * setAttachment103
     *
     * @param SaveAttachmentParameterInterface $attachment103
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function setAttachment103(SaveAttachmentParameterInterface $attachment103): void;
}
