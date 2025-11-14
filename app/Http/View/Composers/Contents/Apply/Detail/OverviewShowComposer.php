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

use GitBalocco\LaravelUiViewComposer\BasicComposer;
use Illuminate\Http\Request;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Application\Usecase\ConfirmApplyCanStartCheckingInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplyBaseInfoInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;

/**
 * OverviewShowComposer
 *
 * @package App\Http\View\Composers\Contents\Apply\Detail
 */
class OverviewShowComposer extends BasicComposer
{
    private RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo;
    private Request $request;
    private ConfirmApplyCanStartCheckingInterface $confirmApplyCanStartChecking;

    public function __construct(
        Request $request,
        ConfirmApplyCanStartCheckingInterface $confirmApplyCanStartChecking,
        RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo,
        private ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus
    ) {
        $this->request = $request;
        $this->confirmApplyCanStartChecking = $confirmApplyCanStartChecking;
        $this->retrieveApplyBaseInfo = $retrieveApplyBaseInfo;
    }

    /**
     * createParameter
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameter(): array
    {
        $applyId = $this->request->route('applyId');
        assert(is_int($applyId));
        $result = $this->confirmApplyCanStartChecking->__invoke($applyId);

        return [
            'id' => $applyId,
            'canStartChecking' => $result->isValid(),
            'errorMessages' => $result->errorMessages(),
            'applyBaseInfo' => $this->retrieveApplyBaseInfo->__invoke($applyId),
            'validationResult' => $result->validationResult(),
            'canDisplayCheckingButton' =>
                $this->validatePermissionChangeApplyStatus->__invoke($applyId, ApplyStatuses::CHECKING_DOCUMENT)
        ];
    }
}
