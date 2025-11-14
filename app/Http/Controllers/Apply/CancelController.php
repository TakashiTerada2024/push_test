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

namespace App\Http\Controllers\Apply;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\ChangeStatusInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;

/**
 * StopController
 *
 * @package App\Http\Controllers\Apply
 */
class CancelController extends Controller
{
    /** @var ChangeStatusInterface $changeStatus */
    private $changeStatus;
    /** @var ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus */
    private $validatePermissionChangeApplyStatus;

    public function __construct(
        ChangeStatusInterface $changeStatus,
        ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus
    ) {
        $this->changeStatus = $changeStatus;
        $this->validatePermissionChangeApplyStatus = $validatePermissionChangeApplyStatus;
    }

    /**
     * __invoke
     *
     * @param Request $request
     * @param int $applyId
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(Request $request, int $applyId)
    {
        if (!$this->validatePermissionChangeApplyStatus->__invoke($applyId, ApplyStatuses::CANCEL)) {
            abort(403);
        }
        $this->changeStatus->__invoke($applyId, ApplyStatuses::CANCEL);

        if (!$request->header('referer')) {
            return Redirect::route('welcome');
        }

        return Redirect::back();
    }
}
