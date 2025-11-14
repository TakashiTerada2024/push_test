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
use App\Http\Requests\Message\Apply\SendRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\UsecaseInteractor\RetrieveApplicantById;
use Ncc01\Messaging\Application\Usecase\SendMessageToApplicantInterface;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\Usecase\SendCommonMessageToApplicantInterface;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Ncc01\User\Application\OutputData\User;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 * SendController
 *
 * @see https://github.com/git-balocco/ncc01/issues/2
 * @package App\Http\Controllers\Message\Apply
 */
class SendController extends Controller
{
    private AuthenticatedUserInterface $authenticatedUser;
    private User $applicant;

    /**
     * @param SendMessageToSecretariatInterface $sendToSecretariatUsecase
     * @param RetrieveApplicantById $retrieveApplicantByIdUsecase
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param ValidatePermissionShowApplyInterface $validatePermissionShowApply
     * @param SendCommonMessageToApplicantInterface $sendCommonMessageToApplicant
     */
    public function __construct(
        private SendMessageToSecretariatInterface $sendToSecretariatUsecase,
        private RetrieveApplicantById $retrieveApplicantByIdUsecase,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        private SendCommonMessageToApplicantInterface $sendCommonMessageToApplicant
    ) {
    }

    /**
     * __invoke
     *
     * @param SendRequest $request
     * @param int $applyId applyId
     * @return \Illuminate\Http\RedirectResponse
     * @SuppressWarnings(PHPMD.StaticAccess) Viewファサードを利用する場合staticアクセスOKとする。
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SendRequest $request, int $applyId)
    {
        $this->init($applyId);

        //権限の検査
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(403);
        }

        $this->process($request, $applyId);
        return Redirect::route('message.apply.show', ['applyId' => $applyId]);
    }

    /**
     * init
     * 初期化処理
     *
     * @param int $applyId
     *
     * @return void
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     *
     */
    private function init(int $applyId): void
    {
        $this->applicant = $this->retrieveApplicantByIdUsecase->__invoke($applyId);
        $this->authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
    }

    /**
     * process
     *
     * @param SendRequest $request
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function process(SendRequest $request, int $applyId)
    {
        if ($this->applicantEqualsToLoginUser()) {
            return $this->sendToSecretariat($request, $applyId);
        }

        return $this->sendToApplicant($request, $applyId);
    }

    /**
     * applicantEqualsToLoginUser
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function applicantEqualsToLoginUser()
    {
        if ($this->authenticatedUser->getId() === $this->applicant->getId()) {
            return true;
        }
        return false;
    }

    /**
     * sendToSecretariat
     *
     * @param SendRequest $request
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function sendToSecretariat(SendRequest $request, int $applyId)
    {
        $parameter = $request->createParameterSendToSecretariat();
        $parameter->setSenderUserId($this->applicant->getId());
        $parameter->setSenderUserName($this->applicant->getName());
        $parameter->setApplyId($applyId);
        return $this->sendToSecretariatUsecase->__invoke($parameter);
    }

    /**
     * sendToApplicant
     *
     * @param SendRequest $request
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function sendToApplicant(SendRequest $request, int $applyId)
    {
        $parameter = $request->createParameterSendToApplicant();
        $parameter->setApplyId($applyId);
        $parameter->setSenderUserName($this->authenticatedUser->getName());
        return $this->sendCommonMessageToApplicant->__invoke($this->applicant->getId(), $parameter);
    }
}
