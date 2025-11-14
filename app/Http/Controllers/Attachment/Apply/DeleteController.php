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

namespace App\Http\Controllers\Attachment\Apply;

use App\Http\Controllers\Controller;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Attachment\Application\Usecase\DeleteAttachmentInterface;
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Ncc01\User\Application\UsecaseInteractor\ValidatePermissionDeleteAttachment;

use function abort;
use function redirect;

/**
 * DeleteController
 *
 * @package App\Http\Controllers\Attachment\Apply
 */
class DeleteController extends Controller
{
    public function __construct(
        private ValidatePermissionDeleteAttachment $validatePermissionDeleteAttachment,
        private DeleteAttachmentInterface $deleteAttachmentUsecase,
        private AttachmentRepositoryInterface $attachmentRepository
    ) {
    }

    public function __invoke($applyId, $id)
    {
        //引数として指定するのは片側のみ
        if (!$this->validatePermissionDeleteAttachment->__invoke(applyId: null, attachmentId: $id)) {
            abort(403);
        }
        $attachment = $this->attachmentRepository->find($id);
        $this->deleteAttachmentUsecase->__invoke($id);
        //テストに合わせた仮実装、実際には前画面へのリダイレクトとなる。
        return redirect()->route($this->buildRedirectPage($attachment), ['applyId' => $applyId]);
    }

    /**
     * buildRedirectPage
     * attachment_type_idからリダイレクト先を組み立てる
     *
     * @param Attachment $attachment
     * @return string
     */
    private function buildRedirectPage($attachment)
    {
        if ($attachment->getAttachmentTypeId() === AttachmentTypes::SECRETARIAT_DOCUMENT) {
            return 'attachment.apply.secretariat.show';
        }
        return 'attachment.apply.show';
    }
}
