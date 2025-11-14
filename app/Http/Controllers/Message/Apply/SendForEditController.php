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

namespace App\Http\Controllers\Message\Apply;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\UsecaseInteractor\RetrieveApplicantById;
use Ncc01\Notification\Application\Usecase\SendMessageForEditToApplicantInterface;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Ncc01\User\Application\OutputData\User;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;
use Ncc01\Messaging\Application\GatewayInterface\MessageRepositoryInterface;
use Illuminate\Support\Facades\App;
use Ncc01\Notification\Application\InputData\SendMessageForEditParameter;
use Ncc01\Notification\Application\InputBoundary\SendMessageForEditParameterInterface;
use Illuminate\Support\Facades\Request;

/**
 * SendForEditController
 *
 * @package App\Http\Controllers\Message\Apply
 */
class SendForEditController extends Controller
{
    private AuthenticatedUserInterface $authenticatedUser;
    private User $applicant;

    /** @var MessageRepositoryInterface $messageRepository */

    /**
     * @param RetrieveApplicantById $retrieveApplicantByIdUsecase
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     * @param SendMessageForEditToApplicantInterface $sendMessageForEditToApplicant
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(
        private RetrieveApplicantById $retrieveApplicantByIdUsecase,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        private SendMessageForEditToApplicantInterface $sendMessageForEditToApplicant,
        private MessageRepositoryInterface $messageRepository
    ) {
    }

    /**
     * __invoke
     *
     * @param string $notificationId
     * @return \Illuminate\Http\RedirectResponse
     * @SuppressWarnings(PHPMD.StaticAccess) Viewファサードを利用する場合staticアクセスOKとする。
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function __invoke(string $notificationId)
    {
        $messageBody = Request::get('messageBody');
        if (!$messageBody) {
            abort(403);
        }

        $message = $this->messageRepository->getMessageByNotificationId($notificationId);
        if (!$message) {
            abort(403);
        }

        $this->init($message->getApplyId());
        //権限の検査
        if (
            !$this->validatePermissionShowApply->__invoke($message->getApplyId())
            || !$message->canEditUser($this->authenticatedUser)
        ) {
            abort(403);
        }

        $this->sendToApplicant($message->getApplyId(), $notificationId, $messageBody);
        return Redirect::route('message.apply.show', ['applyId' => $message->getApplyId()]);
    }

    /**
     * init
     * 初期化処理
     *
     * @param int $applyId
     *
     * @return void
     * @author ushiro <k.ushiro@balocco.info>
     *
     */
    private function init(int $applyId): void
    {
        $this->applicant = $this->retrieveApplicantByIdUsecase->__invoke($applyId);
        $this->authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
    }

    /**
     * sendToApplicant
     *
     * @param int $applyId
     * @param string $notificationId
     * @param string $messageBody
     * @author ushiro <k.ushiro@balocco.info>
     */
    private function sendToApplicant(int $applyId, string $notificationId, string $messageBody)
    {
        /** @var SendMessageForEditParameter $parameter */
        $parameter = App::make(SendMessageForEditParameterInterface::class);

        $parameter->setApplyId($applyId);
        $parameter->setSenderUserName($this->authenticatedUser->getName());
        $parameter->setNotificationId($notificationId);
        $parameter->setMessageBody($messageBody);

        return $this->sendMessageForEditToApplicant->__invoke($this->applicant->getId(), $parameter);
    }
}
