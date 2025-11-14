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

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Exception\InvalidStatusChangeException;
use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\Gateway\ScreenLockRepositoryInterface;
use Ncc01\Apply\Application\Gateway\AttachmentLockRepositoryInterface;
use Ncc01\Apply\Application\Usecase\ChangeStatusInterface;
use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\ScreenLocks;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * ChangeStatus
 *
 * @package Ncc01\Apply\Application\UsecaseInteractor
 */
class ChangeStatus implements ChangeStatusInterface
{
    /** @var ApplyRepositoryInterface $applyRepository */
    private $applyRepository;

    /** @var ScreenLockRepositoryInterface $screenLockRepository */
    private $screenLockRepository;

    /** @var AttachmentLockRepositoryInterface $attachmentLockRepository */
    private $attachmentLockRepository;

    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

    /**
     * ChangeStatus constructor.
     * @param ApplyRepositoryInterface $applyRepository
     * @param ScreenLockRepositoryInterface $screenLockRepository
     * @param AttachmentLockRepositoryInterface $attachmentLockRepository
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        ApplyRepositoryInterface $applyRepository,
        ScreenLockRepositoryInterface $screenLockRepository,
        AttachmentLockRepositoryInterface $attachmentLockRepository,
        RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
        $this->applyRepository = $applyRepository;
        $this->screenLockRepository = $screenLockRepository;
        $this->attachmentLockRepository = $attachmentLockRepository;
        $this->retrieveAuthenticatedUser = $retrieveAuthenticatedUser;
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @param int $statusId
     * @throws InvalidStatusChangeException
     * @throws \LogicException
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId, int $statusId): void
    {
        $apply = $this->applyRepository->findById($applyId);
        $currentStatus = $apply->getStatus();
        $newStatus = new ApplyStatus($statusId);

        // ステータス変更に伴うパラメータを準備
        $params = $this->prepareStatusChangeParams($newStatus);

        // ステータスの更新
        $this->applyRepository->update($params, $applyId);

        // ロック状態の制御
        $this->handleLockControl($applyId, $currentStatus, $newStatus);
    }

    /**
     * ステータス変更に伴うパラメータを準備
     *
     * @param ApplyStatus $newStatus
     * @return array
     */
    private function prepareStatusChangeParams(ApplyStatus $newStatus): array
    {
        $params = ['status' => $newStatus->getValue()];

        if ($newStatus->isAccepted()) {
            $params['accepted_at'] = now();
        }
        if ($newStatus->isSubmittingDocument()) {
            $params['submitted_at'] = now();
        }

        return $params;
    }

    /**
     * ロック状態の制御
     *
     * @param int $applyId
     * @param ApplyStatus $currentStatus
     * @param ApplyStatus $newStatus
     */
    private function handleLockControl(int $applyId, ApplyStatus $currentStatus, ApplyStatus $newStatus): void
    {
        if ($this->shouldUnlock($currentStatus, $newStatus)) {
            $this->updateLockStatus($applyId, false);
        } elseif ($this->shouldLock($currentStatus, $newStatus)) {
            $this->updateLockStatus($applyId, true);
        }
    }

    /**
     * ロック解除が必要かどうかを判定
     *
     * @param ApplyStatus $currentStatus
     * @param ApplyStatus $newStatus
     * @return bool
     */
    private function shouldUnlock(ApplyStatus $currentStatus, ApplyStatus $newStatus): bool
    {
        // 確認中から作成中への変更はロック解除しない(事務局からの差し戻しであるため、ロック状態を維持する)
        if ($currentStatus->isCheckingDocument() && $newStatus->isCreatingDocument()) {
            return false;
        }
        // 提出中から作成中への変更もロック解除しない(事務局からの差し戻しであるため、ロック状態を維持する)
        if ($currentStatus->isSubmittingDocument() && $newStatus->isCreatingDocument()) {
            return false;
        }
        // 確認中でないステータスから作成中への変更はロック解除する
        if ($newStatus->isCreatingDocument()) {
            return true;
        }
        return false;
    }

    /**
     * ロック設定が必要かどうかを判定
     *
     * @param ApplyStatus $currentStatus
     * @param ApplyStatus $newStatus
     * @return bool
     */
    private function shouldLock(ApplyStatus $currentStatus, ApplyStatus $newStatus): bool
    {
        // 作成中から確認中への変更はロックする
        if ($currentStatus->isCreatingDocument() && $newStatus->isCheckingDocument()) {
            return true;
        }
        // 確認中から提出中への変更はロックする
        if ($currentStatus->isCheckingDocument() && $newStatus->isSubmittingDocument()) {
            return true;
        }
        return false;
    }

    /**
     * 画面とファイルのロック状態を更新
     *
     * @param int $applyId
     * @param bool $lockStatus
     */
    private function updateLockStatus(int $applyId, bool $lockStatus): void
    {
        $userId = $this->retrieveAuthenticatedUser->__invoke()->getId();

        // 画面ロックの更新
        $screenLocks = array_fill_keys((new ScreenLocks())->keys(), $lockStatus);
        $this->screenLockRepository->save($applyId, $screenLocks, $userId);

        // 添付ファイルロックの更新
        $attachmentLocks = array_fill_keys((new AttachmentTypes())->listOfId()->all(), $lockStatus);
        $this->attachmentLockRepository->save($applyId, $attachmentLocks, $userId);
    }
}
