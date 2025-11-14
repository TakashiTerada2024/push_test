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
use App\Http\Requests\Apply\ChangeTypeRequest;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\ChangeTypeInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyTypeInterface;

/**
 * ChangeTypeController
 * 申し出種別を変更する
 *
 * @package App\Http\Controllers\Apply
 */
class ChangeTypeController extends Controller
{
    /** @var ChangeTypeInterface $changeType */
    private $changeType;
    /** @var ValidatePermissionChangeApplyTypeInterface $validatePermissionChangeApplyType */
    private $validatePermissionChangeApplyType;

    /**
     * ChangeTypeController constructor.
     * @param ChangeTypeInterface $changeType
     * @param ValidatePermissionChangeApplyTypeInterface $validatePermissionChangeApplyType
     */
    public function __construct(
        ChangeTypeInterface $changeType,
        ValidatePermissionChangeApplyTypeInterface $validatePermissionChangeApplyType
    ) {
        $this->changeType = $changeType;
        $this->validatePermissionChangeApplyType = $validatePermissionChangeApplyType;
    }

    /**
     * __invoke
     *
     * @param ChangeTypeRequest $request
     * @param int $applyId
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(ChangeTypeRequest $request, int $applyId)
    {
        //権限の判定
        if (!$this->validatePermissionChangeApplyType->__invoke()) {
            abort(403);
        }

        //更新処理
        $this->changeType->__invoke($request->createParameter(), $applyId);

        //リダイレクト
        if (!$request->header('referer')) {
            return Redirect::route('welcome');
        }
        return Redirect::back();
    }
}
