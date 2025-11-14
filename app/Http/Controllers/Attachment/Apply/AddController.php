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

namespace App\Http\Controllers\Attachment\Apply;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\Apply\AddRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Ncc01\Messaging\Application\InputData\SendMessageToSecretariatParameter;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;

/**
 * AddController
 * 添付ファイルの追加
 *
 * @package App\Http\Controllers\Attachment\Apply
 */
class AddController extends Controller
{
    private SaveAttachmentInterface $saveAttachment;
    private ValidatePermissionModifyApplyInterface $validatePermissionModifyApply;
    private SendMessageToSecretariatInterface $sendMessageToSecretariat;

    /**
     * @param SaveAttachmentInterface $saveAttachment
     * @param ValidatePermissionModifyApplyInterface $validatePermissionModifyApply
     * @param SendMessageToSecretariatInterface $sendMessageToSecretariat
     */
    public function __construct(
        SaveAttachmentInterface $saveAttachment,
        ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        SendMessageToSecretariatInterface $sendMessageToSecretariat
    ) {
        $this->saveAttachment = $saveAttachment;
        $this->validatePermissionModifyApply = $validatePermissionModifyApply;
        $this->sendMessageToSecretariat = $sendMessageToSecretariat;
    }

    /**
     * __invoke
     *
     * @param AddRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(AddRequest $request, int $id)
    {
        //権限:申出情報の変更を行う権限があるかチェック
        if (!$this->validatePermissionModifyApply->__invoke($id)) {
            abort(403);
        }

        //保存処理
        $parameter = $request->createSaveAttachmentParameter();
        if (!is_null($parameter)) {
            //添付ファイルの保存を実行
            $parameter->setApplyId($id);
            $this->saveAttachment->__invoke($parameter);

            $this->sendMessage($id);
        }


        return Redirect::route('attachment.apply.show', ['applyId' => $id]);
    }

    /**
     * sendMessage
     * 管理者宛のメッセージ送信
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function sendMessage(int $applyId): void
    {
        /** @var SendMessageToSecretariatParameter $parameter */
        $parameter = App::make(SendMessageToSecretariatParameter::class);
        $parameter->setApplyId($applyId);
        $parameter->setMessageBody('（システムによる自動送信）添付ファイルを追加しました。');
        $this->sendMessageToSecretariat->__invoke($parameter);
    }
}
