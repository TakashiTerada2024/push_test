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

namespace App\Http\Controllers\Pdf\Apply;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Ncc01\Apply\Application\Usecase\RetrieveApplyBaseInfoInterface;
use Ncc01\Pdf\Application\InputData\DownloadPdfOfApplyParameter;
use Ncc01\Pdf\Application\Usecase\PdfOfApplyInterface;
use Ncc01\Pdf\Application\Usecase\RetrievePdfViewPathInterface;
use Ncc01\Pdf\Application\Usecase\ValidateCanDisplayPdfOfApplyInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 *
 */
class DownloadController extends Controller
{
    public function __construct(
        private PdfOfApplyInterface $displayPdfOfApply,
        private RetrievePdfViewPathInterface $retrievePdfViewPath,
        private RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        private ValidateCanDisplayPdfOfApplyInterface $validateCanDisplayPdfOfApply,
    ) {
    }

    public function __invoke(int $applyId)
    {
        //申出情報を表示する権限の確認
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(403);
        }

        //表示可能な状態であるかどうかの確認
        $applyBaseInfo = $this->retrieveApplyBaseInfo->__invoke($applyId);

        $applyTypeId = $applyBaseInfo->getTypeId();
        if (!$this->validateCanDisplayPdfOfApply->__invoke($applyBaseInfo->getStatusId(), $applyTypeId)) {
            abort(404);
        }
        assert(!is_null($applyTypeId));

        $parameter = new DownloadPdfOfApplyParameter();
        $parameter->setApplyId($applyId);
        $parameter->setBladeTemplatePath($this->retrievePdfViewPath->__invoke($applyTypeId, 'format'));
        $parameter->setFileName($this->fileName($applyId));
        return $this->displayPdfOfApply->download($parameter);
    }

    private function fileName(int $applyId): string
    {
        $now = Carbon::now()->format('Y-m-d');
        return '申出文書' . (string)$applyId . '_' . $now . '.pdf';
    }
}
