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
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Attachment\Application\Usecase\ChangeAttachmentStatusInterface;
use Ncc01\User\Application\UsecaseInteractor\ValidatePermissionCancelAttachment;

/**
 * CancelController
 * 添付ファイルの提出キャンセル
 *
 * @package App\Http\Controllers\Attachment\Apply
 */
class CancelController extends Controller
{
    /**
     * __construct
     *
     * @param ValidatePermissionCancelAttachment $validatePermissionCancelAttachment
     * @param ChangeAttachmentStatusInterface $changeAttachmentStatus
     */
    public function __construct(
        private ValidatePermissionCancelAttachment $validatePermissionCancelAttachment,
        private ChangeAttachmentStatusInterface $changeAttachmentStatus,
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function __invoke(int $applyId, int $id)
    {
        if (!$this->validatePermissionCancelAttachment->__invoke(applyId: null, attachmentId: $id)) {
            abort(403);
        }

        $this->changeAttachmentStatus->__invoke($id, AttachmentStatuses::UPLOADED);
        return Redirect::route('attachment.apply.show', ['applyId' => $applyId]);
    }
}
