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
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Ncc01\User\Enterprise\Role;

/**
 * DisapproveAttachment
 * 添付ファイルに対する承認取消を行う権限があるかを判定する。
 * 申出情報(apply)、添付ファイル情報(attachment)の二段があるため少し複雑。
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class DisapproveAttachment implements PermissionSpecInterface
{
    /**
     * __construct
     *
     * @param Role $role
     * @param Attachment $attachment
     * @return void
     */
    public function __construct(
        private Role $role,
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
        if (!$this->canDisapproveStatus()) {
            return false;
        }
        if ($this->role->isSuperAdmin()) {
            return true;
        }
        if ($this->role->isSecretariat()) {
            return true;
        }
        if ($this->role->isApplicant()) {
            return false;
        }
        return false;
    }

    /**
     * canDisapproveStatus
     * 「承認済」ステータスのみ承認取消して「アップロード」に変更可能
     *
     * @return bool
     */
    protected function canDisapproveStatus(): bool
    {
        if ($this->attachment->getStatus() == AttachmentStatuses::APPROVED) {
            return true;
        }
        return false;
    }
}
