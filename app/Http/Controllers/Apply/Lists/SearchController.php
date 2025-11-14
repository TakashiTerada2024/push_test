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

namespace App\Http\Controllers\Apply\Lists;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\ApplySearchRequest;
use Illuminate\Support\Facades\View;
use Ncc01\Apply\Application\QueryInterface\ApplySearchInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyListsInterface;

/**
 * SearchController
 * 申出検索機能のコントローラー
 *
 * @package App\Http\Controllers\Apply\Lists
 */
class SearchController extends Controller
{
    /** @var ValidatePermissionShowApplyListsInterface $validatePermissionShowApplyLists */
    private $validatePermissionShowApplyLists;

    /** @var ApplySearchInterface $applySearch */
    private $applySearch;

    /**
     * SearchController constructor.
     *
     * @param ValidatePermissionShowApplyListsInterface $validatePermissionShowApplyLists
     * @param ApplySearchInterface $applySearch
     */
    public function __construct(
        ValidatePermissionShowApplyListsInterface $validatePermissionShowApplyLists,
        ApplySearchInterface $applySearch
    ) {
        $this->validatePermissionShowApplyLists = $validatePermissionShowApplyLists;
        $this->applySearch = $applySearch;
    }

    /**
     * __invoke
     * 申出検索機能を実行する
     *
     * @param ApplySearchRequest $request
     * @return \Illuminate\Contracts\View\View
     * @psalm-suppress InvalidArgument
     */
    public function __invoke(ApplySearchRequest $request)
    {
        // 権限チェック
        if (!$this->validatePermissionShowApplyLists->__invoke()) {
            abort(403);
        }

        // FormRequestからパラメータオブジェクトを取得
        $parameter = $request->toApplySearchParameter();

        // 検索を実行
        $applies = $this->applySearch->__invoke($parameter);

        // ビューを返す
        return View::make('contents.apply.lists.search', [
            'applies' => $applies,
            'keyword' => $request->input('keyword', ''),
            'count' => count($applies) // 検索結果件数
        ]);
    }
}
