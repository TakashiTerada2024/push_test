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

namespace App\Channels;

use App\Models\LatestNotification;
use App\Notifications\HasApplyId;
use App\Notifications\HasNotificationId;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

/**
 * CustomDatabseChannel
 *
 * @package App\Channels
 */
class CustomDatabaseChannel extends DatabaseChannel
{
    /**
     * send
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return \Illuminate\Database\Eloquent\Model
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @todo キュー投入した場合に正常に保存されるかどうかの検証
     */
    public function send($notifiable, Notification $notification)
    {
        $result = parent::send($notifiable, $notification);

        if (is_subclass_of($notification, HasApplyId::class)) {
            //直近通知の保存
            $this->saveLatestNotification($notification->getApplyId(), $notification->id);
        }
        return $result;
    }

    /**
     * buildPayload
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        if (is_subclass_of($notification, HasApplyId::class)) {
            $applyId = $notification->getApplyId();
        }
        if (is_subclass_of($notification, HasNotificationId::class)) {
            $notificationId = $notification->getNotificationId();
        }

        return [
            'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
            'apply_id' => $applyId ?? null,
            'parent_id' => $notificationId ?? null
        ];
    }

    /**
     * saveLatestNotification
     *
     * @param int $applyId
     * @param string $notificationId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function saveLatestNotification(int $applyId, string $notificationId)
    {
        //申出に対する直近の通知IDを保存する。
        LatestNotification::destroy($applyId);
        $model = new LatestNotification();
        $model->apply_id = $applyId;
        $model->notification_id = $notificationId;
        $model->save();
    }
}
