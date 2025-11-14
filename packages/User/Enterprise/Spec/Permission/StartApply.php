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

use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\User\Enterprise\User;

/**
 * StartApply
 * 申請開始（事前相談）の利用権限
 *
 * @package Ncc01\User\Enterprise\Spec\Permission
 */
class StartApply implements PermissionSpecInterface
{
    public function __construct(
        private User $loginUser,
        private ?int $applyId = null,
        private ?int $applicantUserId = null,
        private ?int $applyStatusId = null
    ) {
    }

    public function isSatisfied(): bool
    {
        //ログイン者の権限が利用者ではない場合
        if (!$this->loginUser->getRole()->isApplicant()) {
            //利用不可
            return false;
        }
        //ログイン者が利用者権限
        //申請IDがNULL（申請IDが発番されていない状態）の場合、新規作成であるため許可
        if (is_null($this->applyId)) {
            return true;
        }
        //申請者のuser idががログイン者と一致していない場合不可
        if ($this->loginUser->getId() !== $this->applicantUserId) {
            return false;
        }
        //申請のステータスが事前相談以外の場合不可
        if ($this->applyStatusId !== ApplyStatuses::PRIOR_CONSULTATION) {
            return false;
        }
        return true;
    }
}
