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

namespace Ncc01\Notification\Application\InputData;

use LogicException;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\InputData\SendMessagingNotificationParameter as BaseParameter;
use Ncc01\User\Enterprise\AllSecretariatUser;

/**
 * SendCommonMessageParameter
 *
 * @package Ncc01\Notification\Application\InputData
 */
class SendCommonMessageParameter extends BaseParameter implements SendCommonMessageParameterInterface
{
    private string $messageBody;

    public function __construct(private AllSecretariatUser $allSecretariatUser)
    {
    }


    public function setMessageBody(string $messageBody)
    {
        $this->messageBody = $messageBody;
    }

    public function getMessageBody(): string
    {
        return $this->messageBody;
    }

    /**
     * getSenderUserId
     * 送信者ID：
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getSenderUserId(): int
    {
        return $this->allSecretariatUser->getId();
    }

    public function setSenderUserId(int $senderUserId): void
    {
        throw new LogicException('送信者IDは設定できません');
    }

    public function getSenderUserName(): string
    {
        //窓口組織全体を示す特殊なアカウントのnameを取得
        $allSecretariatUserName = $this->allSecretariatUser->getName();
        //ログイン者の名前が指定されている場合は、カッコつきでの後ろに付け加える
        return $allSecretariatUserName . '（' . parent::getSenderUserName() . '）';
    }
}
