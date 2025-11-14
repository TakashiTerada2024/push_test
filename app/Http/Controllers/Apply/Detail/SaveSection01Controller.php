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

use App\Http\Requests\Apply\Detail\SaveSection01Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Ncc01\Apply\Application\Usecase\RetrieveApplyBaseInfoInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection01Interface;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * Class SaveSection01Controller
 * @package App\Http\Controllers\Apply\Detail
 */
class SaveSection01Controller extends BaseSaveController
{
    private SaveApplySection01Interface $saveApply;
    private RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo;

    public function __construct(
        SaveApplySection01Interface $saveApply,
        ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        SendMessageToSecretariatInterface $sendMessageToSecretariat,
        RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo,
        RetrieveScreenLocksInterface $retrieveScreenLocks
    ) {
        parent::__construct($validatePermissionModifyApply, $sendMessageToSecretariat, $retrieveScreenLocks);
        $this->saveApply = $saveApply;
        $this->retrieveApplyBaseInfo = $retrieveApplyBaseInfo;
    }

    /**
     * __invoke
     *
     * @param SaveSection01Request $request
     * @param int $applyId
     * @return RedirectResponse
     * @SuppressWarnings(PHPMD.StaticAccess) Redirectファサード利用する場合staticアクセスOK。
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveSection01Request $request, int $applyId)
    {
        //表示条件の確認
        $this->confirmConditions($applyId);

        //画面ロックのチェック
        if ($this->isSectionLocked($applyId, 'section1')) {
            abort(403, '画面がロックされているため、保存できません。');
        }

        //様式種別のチェック
        if (!$this->validateApplyType($applyId)) {
            abort(404);
        }

        //保存処理の実行
        $this->saveApply->__invoke($request->createParameter(), $applyId);

        //メッセージ送信
        $this->sendMessageToSecretariat($request->createNotifyToSecretariatParameter($applyId, 1));

        //入力画面の初期表示にリダイレクト
        return Redirect::route('apply.detail.section1', ['applyId' => $applyId]);
    }

    /**
     * checkType
     *
     * @param int $applyId
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function validateApplyType(int $applyId): bool
    {
        return $this->retrieveApplyBaseInfo->__invoke($applyId)->isLinkage();
    }
}
