<?php

namespace App\Http\Controllers\Apply\Lock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apply\Lock\SaveAttachmentLocksRequest;
use Illuminate\Support\Facades\Redirect;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\Apply\Application\Usecase\SaveAttachmentLocksInterface;

class SaveAttachmentLocksController extends Controller
{
    /**
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param SaveAttachmentLocksInterface $saveAttachmentLocks
     */
    public function __construct(
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private SaveAttachmentLocksInterface $saveAttachmentLocks,
    ) {
        $this->middleware('auth');
    }

    /**
     * 添付資料ロック情報を保存
     *
     * @param SaveAttachmentLocksRequest $request
     * @param int $applyId 申請ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(SaveAttachmentLocksRequest $request, int $applyId)
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        if (!$authenticatedUser->isSecretariat()) {
            abort(403);
        }

        try {
            $this->saveAttachmentLocks->__invoke($applyId, $request->createSaveAttachmentLocksParameter());
            return Redirect::route('lock.management', ['applyId' => $applyId])
                ->with('message', '添付資料のロック状態を更新しました。');
        } catch (\Exception $e) {
            report($e);
            return Redirect::route('lock.management', ['applyId' => $applyId])
                ->with('error', '添付資料のロック状態の更新に失敗しました。');
        }
    }
}
