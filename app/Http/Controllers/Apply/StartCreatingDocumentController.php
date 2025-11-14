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
use App\Http\Requests\Apply\ChangeStatusRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\ChangeStatusInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplicantByIdInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\Notification\Application\Usecase\SendStartCreatingDocumentInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionChangeApplyStatusInterface;

/**
 * Class StartCreatingDocumentController
 * 申出文書 作成中ステータスへ遷移させる
 * @package App\Http\Controllers\Apply
 */
class StartCreatingDocumentController extends Controller
{
    public function __construct(
        private ChangeStatusInterface $changeStatus,
        private ValidatePermissionChangeApplyStatusInterface $validatePermissionChangeApplyStatus,
        private RetrieveApplicantByIdInterface $retrieveApplicantById,
        private SendStartCreatingDocumentInterface $sendStartCreatingDocument,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
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
        //権限の確認
        if (
            !$this->validatePermissionChangeApplyStatus->__invoke(
                $applyId,
                ApplyStatuses::CREATING_DOCUMENT
            )
        ) {
            abort(403);
        }

        //指定された申出IDのステータスを変更する
        $this->changeStatus->__invoke($applyId, ApplyStatuses::CREATING_DOCUMENT);

        //通知の送信
        $applicant = $this->retrieveApplicantById->__invoke($applyId);
        $parameter = $this->createNotificationParameter($applyId);
        $this->sendStartCreatingDocument->__invoke($applicant->getId(), $parameter);

        //リダイレクト
        return Redirect::route('apply.lists.prior_consultation');
    }

    private function createNotificationParameter(int $applyId): SendStartCreatingDocumentParameterInterface
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        /** @var SendStartCreatingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartCreatingDocumentParameterInterface::class);
        $parameter->setSenderUserId($authenticatedUser->messageSenderId());
        $parameter->setSenderUserName($authenticatedUser->messageSenderName());
        $parameter->setApplyId($applyId);
        return $parameter;
    }
}
