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

namespace App\Http\Controllers\Apply\Detail;

use App\Http\Requests\Apply\Detail\SaveSection03Request;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\SaveApplySection03Interface;
use Ncc01\Attachment\Application\GatewayInterface\SaveAttachment;
use Ncc01\Attachment\Application\GatewayInterface\SaveAttachmentParameter;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * Class SaveSection03Controller
 * @package App\Http\Controllers\Apply\Detail
 */
class SaveSection03Controller extends BaseSaveController
{
    /** @var SaveApplySection03Interface $saveApplySection03 */
    private $saveApplySection03;

    /**
     * SaveSection03Controller constructor.
     * @param ValidatePermissionModifyApplyInterface $validatePermissionModifyApply
     * @param SaveApplySection03Interface $saveApplySection03
     * @param SendMessageToSecretariatInterface $sendMessageToSecretariat
     * @param RetrieveScreenLocksInterface $retrieveScreenLocks
     */
    public function __construct(
        ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        SaveApplySection03Interface $saveApplySection03,
        SendMessageToSecretariatInterface $sendMessageToSecretariat,
        RetrieveScreenLocksInterface $retrieveScreenLocks
    ) {
        parent::__construct($validatePermissionModifyApply, $sendMessageToSecretariat, $retrieveScreenLocks);
        $this->saveApplySection03 = $saveApplySection03;
    }


    /**
     * __invoke
     *
     * @param SaveSection03Request $request
     * @param int $applyId
     * @return \Illuminate\Http\RedirectResponse
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveSection03Request $request, int $applyId)
    {
        //表示条件の確認
        $this->confirmConditions($applyId);

        //画面ロックのチェック
        if ($this->isSectionLocked($applyId, 'section3')) {
            abort(403, '画面がロックされているため、保存できません。');
        }

        //保存処理
        $this->saveApplySection03->__invoke($request->createSaveParameter(), $applyId);

        //メッセージ送信
        $this->sendMessageToSecretariat($request->createNotifyToSecretariatParameter($applyId, 3));

        //リダイレクト
        return Redirect::route('apply.detail.section3', ['applyId' => $applyId]);
    }
}
