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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace Ncc01\Messaging\Application\InputData;

use LogicException;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * SendMessageToApplicantParameter
 *
 * @package Ncc01\Messaging\Application\Usecase
 */
class SendMessageToApplicantParameter
{
    /** @var int $senderUserId 送信者ID */
    private $senderUserId;
    /** @var string $senderUserName 送信者名 */
    private $senderUserName;
    /** @var string $messageBody メッセージ本文 */
    private $messageBody;
    /** @var int $applyId */
    private $applyId;

    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

    /**
     * SendMessageToSecretariatParameter constructor.
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser)
    {
        $this->retrieveAuthenticatedUser = $retrieveAuthenticatedUser;
    }


    /**
     * getMessageBody
     *
     * @return string
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function getMessageBody(): string
    {
        if (is_null($this->messageBody)) {
            throw new LogicException('messageBody is required.');
        }

        return $this->messageBody;
    }

    /**
     * setMessageBody
     *
     * @param string $messageBody
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function setMessageBody(string $messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /**
     * getApplyId
     *
     * @return int
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function getApplyId(): int
    {
        if (is_null($this->applyId)) {
            throw new LogicException('applyId is required.');
        }
        return $this->applyId;
    }

    /**
     * setApplyId
     *
     * @param int $applyId
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function setApplyId(int $applyId): void
    {
        $this->applyId = $applyId;
    }

    /**
     * @return int
     */
    public function getSenderUserId(): int
    {
        if (is_null($this->senderUserId)) {
            $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
            return $authenticatedUser->getId();
        }

        return $this->senderUserId;
    }

    /**
     * @param int $senderUserId
     */
    public function setSenderUserId(int $senderUserId): void
    {
        $this->senderUserId = $senderUserId;
    }

    /**
     * getSenderUserName
     *
     * @return string
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function getSenderUserName(): string
    {
        if (is_null($this->senderUserName)) {
            $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
            return $authenticatedUser->getName();
        }
        return $this->senderUserName;
    }

    /**
     * setSenderUserName
     *
     * @param string $senderUserName
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function setSenderUserName(string $senderUserName): void
    {
        $this->senderUserName = $senderUserName;
    }
}
