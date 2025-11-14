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

namespace App\Http\Controllers\Attachment\Apply\Secretariat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentsOfApplyBySecretariatInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionAddAttachmentBySecretariatApplyInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentTypeInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowAttachmentBySecretariatApplyInterface;

/**
 * ShowController
 * 事務局から申出に関する添付ファイルを一覧で表示する
 *
 * @package App\Http\Controllers\Attachment\Apply\Secretariat
 */
class ShowController extends Controller
{
    /**
     * ShowController constructor.
     * @param RetrieveAttachmentsOfApplyBySecretariatInterface $retrieveAttachmentsOfApply
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     * @param ValidatePermissionShowAttachmentBySecretariatApplyInterface $validatePermissionShowAttachment
     * @param ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType
     * @param ValidatePermissionAddAttachmentBySecretariatApplyInterface $validatePermissionAddAttachment
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        private RetrieveAttachmentsOfApplyBySecretariatInterface $retrieveAttachmentsOfApply,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        private ValidatePermissionShowAttachmentBySecretariatApplyInterface $validatePermissionShowAttachment,
        private ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType,
        private ValidatePermissionAddAttachmentBySecretariatApplyInterface $validatePermissionAddAttachment,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private AttachmentTypes $attachmentType,
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @return \Illuminate\Contracts\View\View
     * @SuppressWarnings(PHPMD.StaticAccess) コントローラでのViewファサード利用OK
     * m.shomura <m.shomura@balocco.info>
     */
    public function __invoke(int $applyId)
    {
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(403);
        }
        if (!$this->validatePermissionShowAttachment->__invoke($applyId)) {
            abort(403);
        }
        $attachments = $this->retrieveAttachmentsOfApply->__invoke(
            applyId: $applyId,
            isGroup: true,
            typeList: $this->attachmentType->getSecretariatAttachmentList()
        );
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        return View::make(
            'contents.attachment.apply.secretariat.show',
            [
                'id' => $applyId,
                'attachments' => $attachments,
                'canSendAttachmentBySecretariat' => $this->validatePermissionAddAttachment,
                'canModifyType' => $this->validatePermissionModifyAttachmentType->__invoke($applyId),
                'isAdmin' => $authenticatedUser->isSecretariat() || $authenticatedUser->isSuperAdmin(),
            ]
        );
    }
}
