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

namespace Ncc01\User\Enterprise\Spec\Permission;

use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Ncc01\User\Enterprise\Role;

/**
 * SubmitAttachment
 * 添付ファイルに対する提出を行う権限があるかを判定する。
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class SubmitAttachment implements PermissionSpecInterface
{
    /**
     * __construct
     *
     * @param Role $role
     * @param int $loginUserId
     * @param int $applyOwnerUserId
     * @param Attachment $attachment
     * @return void
     */
    public function __construct(
        private Role $role,
        private int $loginUserId,
        private int $applyOwnerUserId,
        private Attachment $attachment,
    ) {
    }

    /**
     * isSatisfied
     *
     * @return bool
     */
    public function isSatisfied(): bool
    {
        if (!$this->canSubmitAttachment()) {
            return false;
        }
        if (!$this->canSubmitStatus()) {
            return false;
        }
        if ($this->role->isSuperAdmin()) {
            return false;
        }
        if ($this->role->isSecretariat()) {
            return false;
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
        return true;
    }

    /**
     * canSubmitStatus
     * 「アップロード」ステータスのみ「提出済」に変更可能
     *
     * @return bool
     */
    protected function canSubmitStatus(): bool
    {
        if ($this->attachment->getStatus() == AttachmentStatuses::UPLOADED) {
            return true;
        }
        return false;
    }

    /**
     * canSubmitAttachment
     * 事務局送付資料は「提出済」に変更不可
     *
     * @return bool
     */
    protected function canSubmitAttachment(): bool
    {
        if ($this->attachment->getAttachmentTypeId() == AttachmentTypes::SECRETARIAT_DOCUMENT) {
            return false;
        }
        return true;
    }
}
