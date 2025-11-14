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

use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\User\Enterprise\Role;

/**
 * ModifyAttachmentType
 * 添付ファイルの種別を変更する権限についての仕様
 * 2023-03-02 改修にて、申出者に対しても添付ファイルの利用を許可する改修を行った。
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ModifyAttachmentType implements PermissionSpecInterface
{
    public function __construct(
        private Role $loginUserRole,
        private int $loginUserId,
        private int $applyUserId,
        private ApplyStatus $applyStatus
    ) {
    }


    public function isSatisfied(): bool
    {
        if ($this->loginUserRole->isSuperAdmin()) {
            return true;
        }
        //事務局の場合、無条件で許可
        if ($this->loginUserRole->isSecretariat()) {
            return true;
        }
        if ($this->loginUserRole->isApplicant()) {
            return $this->applicantLogic();
        }
        return false;
    }

    /**
     * applicantLogic
     * 申出者の場合の判定処理。申出ステータスを見て決める。
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function applicantLogic(): bool
    {
        if ($this->applyUserId !== $this->loginUserId) {
            return false;
        }

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
}
