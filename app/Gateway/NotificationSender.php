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

namespace App\Gateway;

use App\Models\User;
use App\Notifications\CommonMessageNotificationToApplicant;
use App\Notifications\MessageForAddAttachmentBySecretariat;
use App\Notifications\MessageNotificationForEditToApplicant;
use App\Notifications\RemandCheckingDocumentNotification;
use App\Notifications\StartCheckingDocumentNotification;
use App\Notifications\StartCreatingDocumentNotification;
use App\Notifications\StartPriorConsultationNotification;
use App\Notifications\StartSubmittingDocumentNotification;
use Illuminate\Support\Facades\App;
use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendAddAttBySecretariatParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendMessageForEditParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendRemandCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartPriorConsultationParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartSubmittingDocumentParameterInterface;

/**
 * NotificationSender
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package App\Gateway
 */
class NotificationSender implements NotificationSenderInterface
{
    /** @var int $targetUserId */
    private int $targetUserId;

    /**
     * setTargetUserId
     *
     * @param int $targetUserId
     * @return $this
     */
    public function setTargetUserId(int $targetUserId): self
    {
        $this->targetUserId = $targetUserId;
        return $this;
    }

    /**
     * sendStartPriorConsultation
     *
     * 申請者が事前相談作成時に通知
     *
     * @param SendStartPriorConsultationParameterInterface $parameter
     */
    public function sendStartPriorConsultation(
        SendStartPriorConsultationParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);
        //ココで通知をいざ送信っ

        $notification = new StartPriorConsultationNotification(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
            $parameter->getDto()
        );
        $targetUser->notify($notification);
    }

    /**
     * sendStartCreatingDocument
     *
     * 事務局が「申請開始」変更時に通知
     *
     * @param SendStartCreatingDocumentParameterInterface $parameter
     */
    public function sendStartCreatingDocument(
        SendStartCreatingDocumentParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new StartCreatingDocumentNotification(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
        );
        $targetUser->notify($notification);
    }

    /**
     * sendStartCheckingDocument
     *
     * 申請者が「申出文書 承認依頼」実行時に通知
     *
     * @param SendStartCheckingDocumentParameterInterface $parameter
     */
    public function sendStartCheckingDocument(
        SendStartCheckingDocumentParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new StartCheckingDocumentNotification(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
        );
        $targetUser->notify($notification);
    }

    /**
     * sendStartSubmittingDocument
     *
     * 事務局から「承認」変更時に通知
     *
     * @param SendStartSubmittingDocumentParameterInterface $parameter
     */
    public function sendStartSubmittingDocument(
        SendStartSubmittingDocumentParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new StartSubmittingDocumentNotification(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
        );
        $targetUser->notify($notification);
    }

    /**
     * sendRemandCheckingDocument
     *
     * 事務局から「差し戻し」変更時に通知
     *
     * @param SendRemandCheckingDocumentParameterInterface $parameter
     */
    public function sendRemandCheckingDocument(
        SendRemandCheckingDocumentParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new RemandCheckingDocumentNotification(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
        );
        $targetUser->notify($notification);
    }

    /**
     * sendCommonMessageToApplicant
     *
     * 事務局からメッセージ送信時に通知
     *
     * @param SendCommonMessageParameterInterface $parameter
     */
    public function sendCommonMessageToApplicant(
        SendCommonMessageParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new CommonMessageNotificationToApplicant(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
            $parameter->getMessageBody()
        );
        $targetUser->notify($notification);
    }

    /**
     * sendMessageForEditToApplicant
     *
     * 事務局がメッセージ編集時に通知
     *
     * @param SendMessageForEditParameterInterface $parameter
     */
    public function sendMessageForEditToApplicant(
        SendMessageForEditParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new MessageNotificationForEditToApplicant(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
            $parameter->getMessageBody(),
            $parameter->getNotificationId()
        );
        $targetUser->notify($notification);
    }

    /**
     * sendMessageForAddAttachmentBySecretariat
     *
     * 事務局から事務局送付資料追加時に通知
     *
     * @param SendAddAttBySecretariatParameterInterface $parameter
     */
    public function sendMessageForAddAttachmentBySecretariat(
        SendAddAttBySecretariatParameterInterface $parameter
    ) {
        $targetUser = App::make(User::class)->findOrFail($this->targetUserId);

        $notification = new MessageForAddAttachmentBySecretariat(
            $parameter->getApplyId(),
            $parameter->getSenderUserId(),
            $parameter->getSenderUserName(),
            $parameter->getMessageBody()
        );
        $targetUser->notify($notification);
    }
}
