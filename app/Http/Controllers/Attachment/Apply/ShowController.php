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

namespace App\Http\Controllers\Attachment\Apply;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Ncc01\Apply\Application\Usecase\ConfirmApplyCanStartCheckingInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplyBaseInfoInterface;
use Ncc01\Apply\Application\Usecase\RetrieveAttachmentLocksInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentsOfApplyAndTypeInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentTypeInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 * ShowController
 * 申出に関する添付ファイルを一覧で表示する
 *
 * @package App\Http\Controllers\Attachment\Apply
 */
class ShowController extends Controller
{
    /**
     * ShowController constructor.
     * @param RetrieveAttachmentsOfApplyAndTypeInterface $retrieveAttachmentsOfApply
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     * @param ValidatePermissionModifyApplyInterface $validatePermissionModifyApply
     * @param ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType
     * @param ValidatePermissionModifyAttachmentInterface $validatePermissionModifyAttachment
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param ConfirmApplyCanStartCheckingInterface $confirmApplyCanStartChecking
     * @param ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus
     * @param RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo
     * @param AttachmentTypes $attachmentType
     * @param RetrieveAttachmentLocksInterface $retrieveAttachmentLocks
     * @param RetrieveScreenLocksInterface $retrieveScreenLocks
     */
    public function __construct(
        private RetrieveAttachmentsOfApplyAndTypeInterface $retrieveAttachmentsOfApply,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        private ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        private ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType,
        private ValidatePermissionModifyAttachmentInterface $validatePermissionModifyAttachment,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private ConfirmApplyCanStartCheckingInterface $confirmApplyCanStartChecking,
        private ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus,
        private RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo,
        private AttachmentTypes $attachmentType,
        private RetrieveAttachmentLocksInterface $retrieveAttachmentLocks,
        private RetrieveScreenLocksInterface $retrieveScreenLocks,
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @return \Illuminate\Contracts\View\View
     * @SuppressWarnings(PHPMD.StaticAccess) コントローラでのViewファサード利用OK
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId)
    {
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(403);
        }

        $attachments = $this->retrieveAttachmentsOfApply->__invoke(
            applyId: $applyId,
            isGroup: true,
            typeList: $this->attachmentType->getApplicantAttachmentList(),
            includeTypeNull: true
        );
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        $startCheckingResult = $this->confirmApplyCanStartChecking->__invoke($applyId);
        $attachmentLocks = $this->retrieveAttachmentLocks->__invoke($applyId);
        $screenLocks = $this->retrieveScreenLocks->__invoke($applyId);
        $isScreenLocked = $screenLocks['attachment'] ?? false;

        return View::make(
            'contents.attachment.apply.show',
            [
                'id' => $applyId,
                'attachments' => $attachments,
                'canModifyApply' => $this->validatePermissionModifyApply->__invoke($applyId),
                'canModifyType' => $this->validatePermissionModifyAttachmentType->__invoke($applyId),
                'canEditAttachment' => $this->validatePermissionModifyAttachment->__invoke(
                    applyId: $applyId,
                    attachmentId: null
                ),
                'isAdmin' => $authenticatedUser->isSecretariat() || $authenticatedUser->isSuperAdmin(),
                'canStartChecking' => $startCheckingResult->isValid(),
                'canDisplayCheckingButton' =>
                    $this->validatePermissionChangeApplyStatus->__invoke($applyId, ApplyStatuses::CHECKING_DOCUMENT),
                'applyBaseInfo' => $this->retrieveApplyBaseInfo->__invoke($applyId),
                'attachmentLocks' => $attachmentLocks,
                'isScreenLocked' => $isScreenLocked,
            ]
        );
    }
}
