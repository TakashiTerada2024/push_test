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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Http\Controllers\Attachment\Apply\Secretariat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\Apply\Secretariat\AddRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\UsecaseInteractor\RetrieveApplicantById;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Ncc01\Notification\Application\InputBoundary\SendAddAttBySecretariatParameterInterface;
use Ncc01\Notification\Application\Usecase\SendMessageForAddAttachmentBySecretariatInterface;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Ncc01\User\Application\OutputData\User;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionAddAttachmentBySecretariatApplyInterface as ValidateAddAtt;

/**
 * AddController
 * 事務局送付資料の追加
 *
 * @package App\Http\Controllers\Attachment\Apply\Secretariat
 */
class AddController extends Controller
{
    private AuthenticatedUserInterface $authenticatedUser;
    private User $applicant;

    /**
     * @param RetrieveApplicantById $retrieveApplicantByIdUsecase
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     * @param SaveAttachmentInterface $saveAttachment
     * @param ValidateAddAtt $validatePermissionAddAttachmentBySecretariat
     * @param SendMessageForAddAttachmentBySecretariatInterface $sendMessageToApplicant
     * @param AttachmentTypes $attachmentTypes
     */
    public function __construct(
        private RetrieveApplicantById $retrieveApplicantByIdUsecase,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser,
        private SaveAttachmentInterface $saveAttachment,
        private ValidateAddAtt $validatePermissionAddAttachmentBySecretariat,
        private SendMessageForAddAttachmentBySecretariatInterface $sendMessageToApplicant,
        private AttachmentTypes $attachmentTypes
    ) {
    }

    /**
     * __invoke
     *
     * @param AddRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function __invoke(AddRequest $request, int $id)
    {
        // 権限:事務局送付資料の追加を行う権限があるかチェック
        if (!$this->validatePermissionAddAttachmentBySecretariat->__invoke($id)) {
            abort(403);
        }

        $this->init($id);
        // 保存処理
        $parameters = $request->createSaveAttachmentParameter();
        if (empty($parameters)) {
            return Redirect::route('attachment.apply.secretariat.show', ['applyId' => $id]);
        }

        try {
            DB::beginTransaction();
            foreach ($parameters as $parameter) {
                // 添付ファイルの保存を実行
                $parameter->setApplyId($id);
                $parameter->setAttachmentTypeId($this->attachmentTypes::SECRETARIAT_DOCUMENT);
                $this->saveAttachment->__invoke($parameter);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            abort(500);
        }

        $this->sendToApplicant($id);
        return Redirect::route('attachment.apply.secretariat.show', ['applyId' => $id]);
    }

    /**
     * init
     * 初期化処理
     *
     * @param int $applyId
     *
     * @return void
     * @author m.shomura <m.shomura@balocco.info>
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
     * @author m.shomura <m.shomura@balocco.info>
     */
    private function sendToApplicant(int $applyId)
    {
        /** @var SendAddAttBySecretariatParameterInterface $parameter */
        $parameter = App::make(SendAddAttBySecretariatParameterInterface::class);

        $parameter->setApplyId($applyId);
        $parameter->setSenderUserName($this->authenticatedUser->getName());
        $parameter->setMessageBody('（システムによる自動送信）事務局送付資料を追加しました。');

        return $this->sendMessageToApplicant->__invoke($this->applicant->getId(), $parameter);
    }
}
