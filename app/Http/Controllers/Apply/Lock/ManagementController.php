<?php

namespace App\Http\Controllers\Apply\Lock;

use App\Http\Controllers\Controller;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ManagementController extends Controller
{
    /**
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply
    ) {
        $this->middleware('auth');
    }

    /**
     * ロック管理画面を表示
     *
     * @param int $applyId 申請ID
     * @return View|Factory
     */
    public function __invoke(int $applyId)
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(404);
        }

        if (!$authenticatedUser->isSecretariat()) {
            abort(403);
        }

        return view('contents.apply.lock.management', [
            'applyId' => $applyId,
        ]);
    }
}
