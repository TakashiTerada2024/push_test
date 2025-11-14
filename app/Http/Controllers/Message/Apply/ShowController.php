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

namespace App\Http\Controllers\Message\Apply;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Ncc01\Messaging\Application\Usecase\RetrieveMessageOfApplyInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 * ShowController
 * 申出に紐付けられたメッセージの履歴を表示する
 * @package App\Http\Controllers\Messaging\Apply
 */
class ShowController extends Controller
{
    /** @var RetrieveMessageOfApplyInterface $retrieveMessageOfApply */
    private $retrieveMessageOfApply;
    /** @var ValidatePermissionShowApplyInterface $validatePermissionShowApply */
    private $validatePermissionShowApply;
    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

    /**
     * ShowController constructor.
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param RetrieveMessageOfApplyInterface $retrieveMessageOfApply
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     */
    public function __construct(
        RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        RetrieveMessageOfApplyInterface $retrieveMessageOfApply,
        ValidatePermissionShowApplyInterface $validatePermissionShowApply
    ) {
        $this->retrieveAuthenticatedUser = $retrieveAuthenticatedUser;
        $this->retrieveMessageOfApply = $retrieveMessageOfApply;
        $this->validatePermissionShowApply = $validatePermissionShowApply;
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @return \Illuminate\Contracts\View\View
     * @SuppressWarnings(PHPMD.StaticAccess) Viewファサードを利用する場合staticアクセスOKとする。
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId)
    {
        //権限のチェック
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(403);
        }

        //メッセージ拾ってくる処理
        return View::make(
            'contents.message.apply.show',
            [
                'id' => $applyId,
                'authenticatedUser' => $this->retrieveAuthenticatedUser->__invoke(),
                'messages' => $this->retrieveMessageOfApply->__invoke($applyId)
            ]
        );
    }
}
