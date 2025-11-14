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

use LogicException;
use Ncc01\User\Enterprise\Role;

/**
 * ModifyApplyMemo
 * 申出メモの変更権限に関する仕様クラス
 *
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ModifyApplyMemo implements PermissionSpecInterface
{
    /** @var Role $loginUserRole ログイン者のロール */
    private $loginUserRole;

    /**
     * ModifyApplyMemo constructor.
     * @param Role $loginUserRole
     */
    public function __construct(Role $loginUserRole)
    {
        $this->loginUserRole = $loginUserRole;
    }

    /**
     * isSatisfied
     *
     * @return bool
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function isSatisfied(): bool
    {
        // スーパーユーザーに対しては許可
        if ($this->loginUserRole->isSuperAdmin()) {
            return true;
        }
        // 窓口組織に対しては許可
        if ($this->loginUserRole->isSecretariat()) {
            return true;
        }
        // ログイン者が申出者権限の場合不許可
        if ($this->loginUserRole->isApplicant()) {
            return false;
        }
        throw new LogicException('Invalid Role.');
    }
}
