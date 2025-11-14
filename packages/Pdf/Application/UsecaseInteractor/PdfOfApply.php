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

namespace Ncc01\Pdf\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ApplyDetailRepositoryInterface;
use Ncc01\Apply\Application\Gateway\ApplyUserRepositoryInterface;
use Ncc01\Attachment\Application\UsecaseInteractor\RetrieveAttachmentsOfApply;
use Ncc01\Pdf\Application\Gateway\PdfDriverInterface;
use Ncc01\Pdf\Application\InputBoundary\DisplayPdfOfApplyParameterInterface;
use Ncc01\Pdf\Application\InputBoundary\DownloadPdfOfApplyParameterInterface;
use Ncc01\Pdf\Application\Usecase\PdfOfApplyInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * DisplayPdf
 *
 * @package Ncc01\Pdf\Application\UsecaseInteractor
 */
class PdfOfApply implements PdfOfApplyInterface
{
    public function __construct(
        private PdfDriverInterface $pdfDriver,
        private ApplyDetailRepositoryInterface $applyDetailRepository,
        private ApplyUserRepositoryInterface $applyUserRepository,
        private RetrieveAttachmentsOfApply $retrieveAttachmentsOfApply,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
    }

    /**
     * display
     *
     * @param DisplayPdfOfApplyParameterInterface $parameter
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function display(DisplayPdfOfApplyParameterInterface $parameter)
    {
        $templateVars = $this->createVars($parameter->getApplyId());
        //PDFの表示
        return $this->pdfDriver->display($parameter->getBladeTemplatePath(), $templateVars);
    }

    /**
     * download
     *
     * @param DownloadPdfOfApplyParameterInterface $parameter
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function download(DownloadPdfOfApplyParameterInterface $parameter)
    {
        $templateVars = $this->createVars($parameter->getApplyId());
        return $this->pdfDriver->download($parameter->getBladeTemplatePath(), $templateVars, $parameter->getFileName());
    }

    /**
     * createVars
     *
     * @param int $applyId
     * @return array
     */
    protected function createVars(int $applyId): array
    {
        $detail = $this->applyDetailRepository->findById($applyId);
        $applyUsers = $this->applyUserRepository->find($applyId);
        $attachments = $this->retrieveAttachmentsOfApply->__invoke(
            applyId: $applyId,
            isGroup: true,
            roleList: [3],
            statusList: [2, 3]
        );
        return [
            'applyDetail' => $detail,
            'applyUsers' => $applyUsers,
            'attachments' => $attachments,
            'isApplicant' => $this->retrieveAuthenticatedUser->__invoke()->isApplicant()
        ];
    }
}
