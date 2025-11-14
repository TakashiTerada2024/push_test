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
 * 1件の申出に関連する情報を閲覧する許可。
 *
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ShowInformationAboutApply implements PermissionSpecInterface
{
    private ApplyStatus $applyStatus;

    public function __construct(
        private Role $loginUserRole,
        private int $loginUserId,
        private int $applyUserId,
        private int $applyStatusId
    ) {
        $this->applyStatus = new ApplyStatus($applyStatusId);
    }

    /**
     * isSatisfied
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSatisfied(): bool
    {
        if (!$this->validateRole()) {
            return false;
        }
        return true;
    }

    private function validateRole(): bool
    {
        //ログイン者がスーパーユーザーならOK
        if ($this->loginUserRole->isSuperAdmin()) {
            return true;
        }
        //ログイン者が窓口組織ならOK
        if ($this->loginUserRole->isSecretariat()) {
            return true;
        }
        //ログイン者が申出者権限の場合
        if ($this->loginUserRole->isApplicant()) {
            //申出の所有者===ログイン者　ならOK
            return ($this->loginUserId === $this->applyUserId);
        }
        return false;
    }
}
