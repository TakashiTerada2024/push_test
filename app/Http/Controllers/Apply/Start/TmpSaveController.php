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

namespace App\Http\Controllers\Apply\Start;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\Start\TmpSaveRequest;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\CreateApplyInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionStartApplyInterface;

/**
 * TmpSaveController
 *
 * @package App\Http\Controllers\Apply\Start
 */
class TmpSaveController extends Controller
{
    public function __construct(
        private CreateApplyInterface $createApply,
        private ValidatePermissionStartApplyInterface $validatePermissionStartApply
    ) {
    }

    public function __invoke(TmpSaveRequest $request, ?int $applyId = null)
    {
        //権限：申出者アカウント以外は利用不可
        if (!$this->validatePermissionStartApply->__invoke($applyId)) {
            abort(403);
        }

        //保存
        $this->createApply->__invoke($request->createParameter(), $applyId);
        //申し出一覧へリダイレクト
        return Redirect::route('apply.lists.my_list');
    }
}
