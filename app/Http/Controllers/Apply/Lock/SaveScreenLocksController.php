<?php

namespace App\Http\Controllers\Apply\Lock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\Lock\SaveScreenLocksRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\Apply\Application\Usecase\SaveScreenLocksInterface;

class SaveScreenLocksController extends Controller
{
    /**
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param SaveScreenLocksInterface $saveScreenLocks
     */
    public function __construct(
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private SaveScreenLocksInterface $saveScreenLocks,
    ) {
        $this->middleware('auth');
    }

    /**
     * 画面ロック情報を保存
     *
     * @param SaveScreenLocksRequest $request
     * @param int $applyId 申請ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(SaveScreenLocksRequest $request, int $applyId)
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        if (!$authenticatedUser->isSecretariat()) {
            abort(403);
        }

        try {
            $this->saveScreenLocks->__invoke($applyId, $request->createSaveScreenLocksParameter());
            return Redirect::route('lock.management', ['applyId' => $applyId])
                ->with('message', 'ロック状態を更新しました。');
        } catch (\Exception $e) {
            report($e);
            return Redirect::route('lock.management', ['applyId' => $applyId])
                ->with('error', 'ロック状態の更新に失敗しました。');
        }
    }
}
