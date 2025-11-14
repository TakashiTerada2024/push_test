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

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 * DownloadController
 *
 * @package App\Http\Controllers\Attachment
 */
class DownloadController extends Controller
{
    /** @var RetrieveAttachmentInterface $retrieveAttachment */
    private $retrieveAttachment;
    /** @var ValidatePermissionShowApplyInterface $validatePermissionShowApply */
    private $validatePermissionShowApply;

    /**
     * DownloadController constructor.
     * @param RetrieveAttachmentInterface $retrieveAttachment
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     */
    public function __construct(
        RetrieveAttachmentInterface $retrieveAttachment,
        ValidatePermissionShowApplyInterface $validatePermissionShowApply
    ) {
        $this->retrieveAttachment = $retrieveAttachment;
        $this->validatePermissionShowApply = $validatePermissionShowApply;
    }

    /**
     * __invoke
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __invoke(int $id)
    {
        //添付ファイル情報を取得
        $result = $this->retrieveAttachment->__invoke($id);
        //権限チェック
        if (!$this->validatePermissionShowApply->__invoke($result->getApplyId())) {
            abort(403);
        }

        //ダウンロードレスポンス
        return Response::download($result->getPath(), (string)$id . '_' . $result->getName());
    }
}
