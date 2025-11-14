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

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * WelcomeController
 * ログイン直後に遷移してくる画面
 *
 * @package App\Http\Controllers
 */
class WelcomeController extends Controller
{
    private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser;

    /**
     * ListController constructor.
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser)
    {
        $this->retrieveAuthenticatedUser = $retrieveAuthenticatedUser;
    }

    public function __invoke()
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        if ($authenticatedUser->isSuperAdmin() || $authenticatedUser->isSecretariat()) {
            return Redirect::route('apply.lists.search');
        }

        if ($authenticatedUser->isApplicant()) {
            return Redirect::route('apply.lists.my_list');
        }
    }
}
