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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\ChangeStatusInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplicantByIdInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Notification\Application\InputBoundary\SendRemandCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\Usecase\SendRemandCheckingDocumentInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;
use Ncc01\User\Application\UsecaseInteractor\RetrieveAuthenticatedUser;

/**
 * 承認依頼の差し戻し
 * @package App\Http\Controllers\Apply
 */
class RemandCheckingDocumentController extends Controller
{
    public function __construct(
        private ChangeStatusInterface $changeStatus,
        private ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus,
        private RetrieveApplicantByIdInterface $retrieveApplicantById,
        private SendRemandCheckingDocumentInterface $sendRemandCheckingDocument,
        private RetrieveAuthenticatedUser $retrieveAuthenticatedUser
    ) {
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
        //権限のチェック
        if (
            !$this->validatePermissionChangeApplyStatus->__invoke(
                $applyId,
                ApplyStatuses::CREATING_DOCUMENT
            )
        ) {
            abort(403);
        }
        //申し出文書作成中ステータスへの変更を実施
        $this->changeStatus->__invoke($applyId, ApplyStatuses::CREATING_DOCUMENT);

        $this->sendRemandCheckingDocument->__invoke(
            $this->retrieveApplicantById->__invoke($applyId)->getId(),
            $this->notificationParameter($applyId)
        );

        //リダイレクト
        if (!$request->header('referer')) {
            return Redirect::route('welcome');
        }
        return Redirect::back();
    }

    /**
     * notificationParameter
     *
     * @param int $applyId
     * @return SendRemandCheckingDocumentParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function notificationParameter(int $applyId): SendRemandCheckingDocumentParameterInterface
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        /** @var SendRemandCheckingDocumentParameterInterface $parameter */
        $parameter = App::make(SendRemandCheckingDocumentParameterInterface::class);
        $parameter->setApplyId($applyId);
        $parameter->setSenderUserId($authenticatedUser->messageSenderId());
        $parameter->setSenderUserName($authenticatedUser->messageSenderName());
        return $parameter;
    }
}
