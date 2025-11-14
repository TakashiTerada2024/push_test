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
 * ChangeApplyStatus
 *
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class ChangeApplyStatus implements PermissionSpecInterface
{
    /** @var Role $loginUserRole */
    private $loginUserRole;
    /** @var int $loginUserId */
    private $loginUserId;
    /** @var int $applyUserId */
    private $applyUserId;
    /** @var ApplyStatus $applyStatusIdToChange */
    private $applyStatusIdToChange;

    /**
     * ChangeApplyStatus constructor.
     * @param Role $loginUserRole
     * @param int $loginUserId
     * @param int $applyUserId
     * @param ApplyStatus $applyStatusIdToChange
     */
    public function __construct(
        Role $loginUserRole,
        int $loginUserId,
        int $applyUserId,
        ApplyStatus $applyStatusIdToChange
    ) {
        $this->loginUserRole = $loginUserRole;
        $this->loginUserId = $loginUserId;
        $this->applyUserId = $applyUserId;
        $this->applyStatusIdToChange = $applyStatusIdToChange;
    }

    /**
     * isSatisfied
     *
     * @return bool
     * @todo リファクタリング
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function isSatisfied(): bool
    {
        //遷移先のステータスが文書作成中である場合

        if ($this->applyStatusIdToChange->isCreatingDocument()) {
            //事務局のみ変更可。
            return $this->loginUserRole->isSecretariat();
        }
        //遷移先のステータスが文書確認中である場合
        if ($this->applyStatusIdToChange->isCheckingDocument()) {
            //申出者本人のみ可。
            return (($this->loginUserId === $this->applyUserId) && $this->loginUserRole->isApplicant());
        }
        //遷移先のステータスが文書提出中である場合
        if ($this->applyStatusIdToChange->isSubmittingDocument()) {
            //事務局のみ可
            return $this->loginUserRole->isSecretariat();
        }

        //遷移先のステータスが中止である場合
        if ($this->applyStatusIdToChange->isCancel()) {
            //事務局のみ変更可。
            return $this->loginUserRole->isSecretariat();
        }
        //遷移先のステータスが応諾である場合
        if ($this->applyStatusIdToChange->isAccepted()) {
            //事務局のみ変更可。
            return $this->loginUserRole->isSecretariat();
        }

        //それ以外は不許可として仮実装。（今後、実装の必要に応じて拡張する）
        return false;
    }
}
