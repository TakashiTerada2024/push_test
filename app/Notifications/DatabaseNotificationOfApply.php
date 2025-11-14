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

use Illuminate\Notifications\Notification;

/**
 * DatabaseNotificationOfApply
 *
 * @package App\Notifications
 */
class DatabaseNotificationOfApply extends Notification implements HasApplyId
{
    /** @var array $payload */
    private $payload;
    /** @var int $applyId */
    private $applyId;

    /**
     * DatabaseNotificationOfApply constructor.
     * @param array $payload
     * @param int $applyId
     */
    public function __construct(array $payload, int $applyId)
    {
        $this->payload = $payload;
        $this->applyId = $applyId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * toDatabase
     *
     * @param $notifiable
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function toDatabase($notifiable): array
    {
        return $this->payload;
    }

    /**
     * getApplyId
     *
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getApplyId(): int
    {
        return $this->applyId;
    }
}
