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
use App\Http\Requests\Attachment\Apply\ChooseTypeRequest;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Attachment\Application\Usecase\ModifyAttachmentTypeInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyAttachmentTypeInterface;

/**
 * ChooseTypeController
 *
 * @package App\Http\Controllers\Attachment\Apply
 */
class ChooseTypeController extends Controller
{
    /**
     * ChooseTypeController constructor.
     * @param ModifyAttachmentTypeInterface $modifyAttachmentType
     * @param ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType
     */
    public function __construct(
        private ModifyAttachmentTypeInterface $modifyAttachmentType,
        private ValidatePermissionModifyAttachmentTypeInterface $validatePermissionModifyAttachmentType
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @param ChooseTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId, ChooseTypeRequest $request)
    {
        //権限チェック
        if (!$this->validatePermissionModifyAttachmentType->__invoke($applyId)) {
            abort(403);
        }

        foreach ($request->createParameters() as $attachmentId => $parameter) {
            $this->modifyAttachmentType->__invoke($attachmentId, $parameter);
        }

        //種別の変更を保存
        return Redirect::route('attachment.apply.show', ['applyId' => $applyId]);
    }
}
