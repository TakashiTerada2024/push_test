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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\ChangeStatusInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Notification\Application\InputBoundary\SendStartCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\Usecase\SendStartCheckingDocumentInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;

/**
 * StartConfirmingDocumentController
 *
 * @package App\Http\Controllers\Apply
 */
class StartCheckingDocumentController extends Controller
{
    public function __construct(
        private ChangeStatusInterface $changeStatus,
        private ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus,
        private SendStartCheckingDocumentInterface $sendStartCheckingDocument,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $applyId)
    {
        //権限のチェック
        if (!$this->validatePermissionChangeApplyStatus->__invoke($applyId, ApplyStatuses::CHECKING_DOCUMENT)) {
            abort(403);
        }
        //申し出文書確認中ステータスへの変更を実施
        $this->changeStatus->__invoke($applyId, ApplyStatuses::CHECKING_DOCUMENT);
        //通知の送信
        $this->sendStartCheckingDocument->__invoke($this->notificationParameter($applyId));

        //リダイレクト
        return Redirect::route('apply.detail.overview', ['applyId' => $applyId]);
    }

    /**
     * notificationParameter
     *
     * @param int $applyId
     * @return SendStartCheckingDocumentParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function notificationParameter(int $applyId): SendStartCheckingDocumentParameterInterface
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        /** @var SendStartCheckingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartCheckingDocumentParameterInterface::class);
        $parameter->setApplyId($applyId);
        $parameter->setSenderUserId($authenticatedUser->messageSenderId());
        $parameter->setSenderUserName($authenticatedUser->messageSenderName());
        return $parameter;
    }
}
