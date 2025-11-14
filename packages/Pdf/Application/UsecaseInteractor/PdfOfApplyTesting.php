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

namespace Ncc01\Pdf\Application\UsecaseInteractor;

use Ncc01\Pdf\Application\UsecaseInteractor\PdfOfApply;
use Ncc01\Pdf\Application\InputBoundary\DownloadPdfOfApplyParameterInterface;
use Ncc01\Pdf\Application\Usecase\PdfOfApplyInterface;
use Illuminate\Support\Facades\View;

/**
 * PdfOfApplyTesting
 * テスト用PDF出力処理
 *
 * @package Ncc01\Pdf\Application\UsecaseInteractor
 */
class PdfOfApplyTesting extends PdfOfApply implements PdfOfApplyInterface
{
    /**
     * download
     *
     * テスト検証のためPDFファイルではなく同内容のviewを返却する
     *
     * @param DownloadPdfOfApplyParameterInterface $parameter
     * @return \Illuminate\Contracts\View\View
     * @SuppressWarnings(PHPMD.StaticAccess) Viewファサードを利用する場合staticアクセスOKとする。
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function download(DownloadPdfOfApplyParameterInterface $parameter)
    {
        $templateVars = $this->createVars($parameter->getApplyId());
        return View::make($parameter->getBladeTemplatePath(), $templateVars);
    }
}
