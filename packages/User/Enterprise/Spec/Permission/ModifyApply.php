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

use LogicException;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Entity\ApplyStatus;
use Ncc01\User\Enterprise\Role;

/**
 * ModifyApply
 * 申出情報の変更権限に関する仕様クラス
 *
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ModifyApply implements PermissionSpecInterface
{
    /** @var Role $loginUserRole ログイン者のロール */
    private $loginUserRole;
    /** @var int $loginUserId ログイン者のID */
    private $loginUserId;
    /** @var int $applicantUserId 検査対象の申出所有者ID */
    private $applyUserId;
    /** @var ApplyStatus $applyStatus */
    private $applyStatus;

    /**
     * ModifyApply constructor.
     * @param Role $loginUserRole
     * @param int $loginUserId
     * @param int $applyUserId
     * @param ApplyStatus $applyStatus
     */
    public function __construct(Role $loginUserRole, int $loginUserId, int $applyUserId, ApplyStatus $applyStatus)
    {
        $this->loginUserRole = $loginUserRole;
        $this->loginUserId = $loginUserId;
        $this->applyUserId = $applyUserId;
        $this->applyStatus = $applyStatus;
    }

    /**
     * isSatisfied
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSatisfied(): bool
    {
        //ステータスが審査中以降である場合、禁止
        if ($this->applyStatus->getValue() >= ApplyStatuses::UNDER_REVIEW) {
            return false;
        }
        //スーパーユーザーに対しては許可
        if ($this->loginUserRole->isSuperAdmin()) {
            return true;
        }
        //窓口組織に対しては不許可
        if ($this->loginUserRole->isSecretariat()) {
            return false;
        }
        //ログイン者が申出者権限の場合。
        if ($this->loginUserRole->isApplicant()) {
            //申出の所有者===ログイン者　ならOK
            return ($this->loginUserId === $this->applyUserId);
        }
        throw new LogicException('Invalid Role.');
    }
}
