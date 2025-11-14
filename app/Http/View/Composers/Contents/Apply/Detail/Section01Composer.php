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

namespace App\Http\View\Composers\Contents\Apply\Detail;

use GitBalocco\LaravelUiViewComposer\Contract\ViewComposerInterface;
use GitBalocco\LaravelUiViewComposer\Contract\ViewParameterCreator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Ncc01\Attachment\Application\Usecase\RetrieveLatestAttachmentOfTypeInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;

/**
 * Section02ViewComposer
 *
 * @package App\Http\View\Composers\Contents\Apply\Detail
 */
class Section01Composer extends ShowSectionBaseComposer implements ViewComposerInterface, ViewParameterCreator
{
    private RetrieveLatestAttachmentOfTypeInterface $retrieveLatestAttachmentOfType;

    /**
     * Section02Composer constructor.
     * @param Request $request
     * @param ValidatePermissionModifyApplyInterface $validatePermissionModifyApply
     * @param RetrieveLatestAttachmentOfTypeInterface $retrieveLatestAttachmentOfType
     */
    public function __construct(
        Request $request,
        ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        RetrieveLatestAttachmentOfTypeInterface $retrieveLatestAttachmentOfType
    ) {
        $this->retrieveLatestAttachmentOfType = $retrieveLatestAttachmentOfType;
        parent::__construct($request, $validatePermissionModifyApply);
    }


    /**
     * createParameter
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameter(): array
    {
        /** @var int $applyId */
        $applyId = $this->getRequest()->route('applyId');

        return array_merge(
            parent::createParameter(),
            [
                'id' => $applyId,
                'attachment101' => $this->retrieveLatestAttachmentOfType->__invoke($applyId, 101),
                'attachment102' => $this->retrieveLatestAttachmentOfType->__invoke($applyId, 102),
                'attachment103' => $this->retrieveLatestAttachmentOfType->__invoke($applyId, 103),
            ]
        );
    }

    /**
     * init
     *
     * @param Request $request
     * @param View $view
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function init(Request $request, View $view): void
    {
        //DO nothing
    }
}
