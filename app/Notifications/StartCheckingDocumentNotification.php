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

namespace App\Notifications;

use App\Channels\CustomDatabaseChannel;
use App\Common\BareMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\View;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class StartCheckingDocumentNotification extends Notification implements HasApplyId
{
    use Queueable;

    public function __construct(
        private int $applyId,
        private int $senderUserId,
        private string $senderUserName,
    ) {
    }

    public function getApplyId(): int
    {
        return $this->applyId;
    }

    public function via($notifiable)
    {
        return ['mail', CustomDatabaseChannel::class];
    }

    /**
     * toMail
     *
     * @param $notifiable
     * @return BareMail
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function toMail($notifiable)
    {
        $mailable = new BareMail();
        $mailable->to($notifiable->routeNotificationFor('mail'));
        $mailable->subject('承認依頼');
        $mailable->text(
            'notifications.start-checking-document-mail',
            [
                'applyId' => $this->applyId,
                'senderUserId' => $this->senderUserId,
                'senderUserName' => $this->senderUserName
            ]
        );
        return $mailable;
    }

    /**
     * toArray
     *
     * @param $notifiable
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function toArray($notifiable)
    {
        $vars = [
            'applyId' => $this->applyId,
            'senderUserId' => $this->senderUserId,
            'senderUserName' => $this->senderUserName
        ];
        $body = View::make('notifications.start-checking-document-messaging', $vars)->render();

        return [
            'body' => $body,
            'fromId' => $this->senderUserId,
            'fromName' => $this->senderUserName
        ];
    }
}
