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

namespace App\Gateway\Repository\Messaging;

use App\Common\JsonDecode;
use App\Models\Notification;
use Illuminate\Support\Facades\App;
use Ncc01\Messaging\Application\GatewayInterface\MessageRepositoryInterface;
use Ncc01\Messaging\Application\OutputBoundary\MessagesInterface;
use Ncc01\Messaging\Application\OutputData\Message;
use Ncc01\Messaging\Application\OutputData\Messages;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * MessageRepository
 *
 * @package App\Repository\Messaging
 * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
 */
class MessageRepository implements MessageRepositoryInterface
{
    /**
     * getMessageOfApply
     *
     * @param int $applyId
     * @return MessagesInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getMessageOfApply(int $applyId): MessagesInterface
    {
        return new Messages($this->messages($applyId));
    }

    /**
     * getMessageOfApply
     *
     * @param int $applyId
     * @return MessagesInterface
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function getLatestMessageOfApply(int $applyId): MessagesInterface
    {
        return new Messages($this->latestMessages($applyId));
    }

    /**
     * markAsRead
     *
     * @param int $applyId
     * @param AuthenticatedUserInterface $loginUser
     * @todo リファクタリング。ログイン権限により処理の分岐を行っている箇所は、Specとしてまとめるべき内容である。
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function markAsRead(int $applyId, AuthenticatedUserInterface $loginUser): void
    {
        if ($loginUser->isSuperAdmin()) {
            return;
        }

        Notification::where('apply_id', $applyId)
            ->where('notifiable_id', '=', $this->notifiableIdMarkAsRead($loginUser))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * messages
     *
     * @param int $applyId
     * @return \Generator
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function messages(int $applyId)
    {
        $cursor = Notification::where('apply_id', $applyId)->orderBy('created_at', 'DESC')->cursor();
        foreach ($cursor as $item) {
            yield $this->createMessage($item);
        }
    }

    /**
     * latestMessages
     *
     * 対象のapplyIdのメッセージ一覧を取得する。
     * メッセージが更新されていた場合、その更新内容で取得する。
     *
     * @param int $applyId
     * @return \Generator
     * @author ushiro <k.ushiro@balocco.info>
     */
    private function latestMessages(int $applyId)
    {
        //更新以外のメッセージを取得する
        $cursor = Notification::where('apply_id', $applyId)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'DESC')
            ->cursor();

        //更新メッセージのid取得ロジックを生成する
        $lastNotification =
            Notification::select('parent_id', DB::raw('MAX(created_at) as last_notification_created_at'))
            ->whereRaw('apply_id = ?', [$applyId])
            ->whereNotNull('parent_id')
            ->groupBy('parent_id');

        //更新メッセージidから、メッセージレコードを取得する
        $cursorUpdated =
            Notification::joinSub($lastNotification, 'last_notification', function ($join) {
                $join->on('notifications.created_at', '=', 'last_notification.last_notification_created_at');
            })
            ->whereRaw('apply_id = ?', [$applyId])
            ->orderBy('created_at', 'DESC')
            ->cursor();

        $updateItems = [];
        foreach ($cursorUpdated as $item) {
            $updateItems[$item->parent_id] = $item;
        }

        foreach ($cursor as $item) {
            $message = $this->createMessage($item);
            //更新メッセージがあるか？
            if (isset($updateItems[$message->getId()])) {
                $updateMessage = $this->createMessage($updateItems[$message->getId()]);
                //あれば、更新メッセージのbody（内容）を採用する
                $message->setBody($updateMessage->getBody());
                $message->setLastUpdatedAt($updateMessage->getUpdatedAt());
            }
            yield $message;
        }
    }

    /**
     * createMessage
     *
     * @param Notification $notification
     * @return Message
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function createMessage(Notification $notification): Message
    {
        /** @var JsonDecode $jsonDecode */
        $jsonDecode = App::make(JsonDecode::class);
        $decodedData = $jsonDecode->__invoke($notification->data);
        return new Message(
            $decodedData->body,
            $decodedData->fromId,
            $decodedData->fromName,
            $notification->created_at,
            $notification->updated_at,
            $notification->id,
            $notification->apply_id,
            $notification->parent_id,
        );
    }

    /**
     * notifiableIdMarkAsRead
     *
     * @param AuthenticatedUserInterface $loginUser
     * @return int|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @todo リファクタリング。ログイン権限により処理の分岐を行っている箇所は、Specとしてまとめるべき内容である。
     */
    private function notifiableIdMarkAsRead(AuthenticatedUserInterface $loginUser): ?int
    {
        if ($loginUser->isSecretariat()) {
            return 2;
        }
        return $loginUser->getId();
    }

    /**
     * getMessageByNotificationId
     *
     * @param string $notificationId
     * @return Message|null
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function getMessageByNotificationId(string $notificationId): ?Message
    {
        if (!Str::isUuid($notificationId)) {
            return null;
        }
        $notification = Notification::where('id', $notificationId)->first();
        return $this->createMessage($notification);
    }
}
