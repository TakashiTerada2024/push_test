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

namespace Ncc01\User\Enterprise\Spec\Permission;

use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Ncc01\User\Enterprise\Role;

/**
 * ModifyAttachment
 * 添付ファイルに対する変更を行う権限があるかを判定する。
 * 申出情報(apply)、添付ファイル情報(attachment)の二段があるため少し複雑。
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ModifyAttachment implements PermissionSpecInterface
{
    public function __construct(
        private Role $role,
        private int $loginUserId,
        private ApplyStatus $applyStatus,
        private int $applyOwnerUserId,
        private ?int $userIdWhoHasAttachment,
        private ?Attachment $attachment,
    ) {
    }

    /**
     * isSatisfied
     *
     * @return bool
     */
    public function isSatisfied(): bool
    {
        if ($this->role->isSuperAdmin()) {
            return true;
        }
        if ($this->role->isSecretariat()) {
            return true;
        }
        if ($this->role->isApplicant()) {
            return $this->applicantLogic();
        }
        return false;
    }

    /**
     * applicantLogic
     *
     * @return bool
     */
    private function applicantLogic(): bool
    {
        // ログイン者と、申出の所有者の一致を確認
        if ($this->loginUserId !== $this->applyOwnerUserId) {
            return false;
        }
        // 申出ステータス確認
        if (!$this->applyStatusCheck()) {
            return false;
        }
        // 添付ファイルの所有者の確認
        return $this->attachmentOwnerUserCheck();
    }

    /**
     * applyStatusCheck
     *
     * @return bool
     */
    private function applyStatusCheck(): bool
    {
        if ($this->applyStatus->isPriorConsultation()) {
            return true;
        }
        if ($this->applyStatus->isCreatingDocument()) {
            return true;
        }
        if ($this->applyStatus->isCheckingDocument()) {
            return true;
        }
        if ($this->applyStatus->isSubmittingDocument()) {
            return true;
        }
        return false;
    }

    /**
     * attachmentOwnerUserCheck
     *
     * @return bool
     */
    private function attachmentOwnerUserCheck(): bool
    {
        //事前に添付ファイルの所有者を確定できない状況では、判定せずにtrueを返す（申出の所有者が確認できていることが前提）
        if (is_null($this->userIdWhoHasAttachment)) {
            return true;
        }

        /**
         * 添付ファイルの所有者と、ログイン者の一致の確認。
         * 2023-03-03 USECASE側で制御しているため、通常この結果がFALSEになることはない。
         * @see ValidatePermissionDeleteAttachment::checkArguments()
         * 今後の追加開発時に開発者が使い方を間違えた場合のため、念のため一致していることを検証している。
         */
        return ($this->loginUserId === $this->userIdWhoHasAttachment);
    }
}
