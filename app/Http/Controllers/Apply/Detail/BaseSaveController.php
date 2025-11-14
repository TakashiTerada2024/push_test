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

use App\Http\Controllers\Controller;
use Ncc01\Messaging\Application\InputData\SendMessageToSecretariatParameter;
use Ncc01\Messaging\Application\Usecase\SendMessageToSecretariatInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionModifyApplyInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;

/**
 * BaseSaveController
 *
 * @codeCoverageIgnore コントローラーの抽象クラスは計測対象から除外
 * @package App\Http\Controllers\Apply\Detail
 */
abstract class BaseSaveController extends Controller
{
    /** @var ValidatePermissionModifyApplyInterface $validatePermissionModifyApply */
    private $validatePermissionModifyApply;
    /** @var SendMessageToSecretariatInterface $sendMessageToSecretariat */
    private $sendMessageToSecretariat;
    /** @var RetrieveScreenLocksInterface $retrieveScreenLocks */
    private $retrieveScreenLocks;

    /**
     * BaseSaveController constructor.
     * @param ValidatePermissionModifyApplyInterface $validatePermissionModifyApply
     * @param SendMessageToSecretariatInterface $sendMessageToSecretariat
     * @param RetrieveScreenLocksInterface $retrieveScreenLocks
     */
    public function __construct(
        ValidatePermissionModifyApplyInterface $validatePermissionModifyApply,
        SendMessageToSecretariatInterface $sendMessageToSecretariat,
        RetrieveScreenLocksInterface $retrieveScreenLocks
    ) {
        $this->validatePermissionModifyApply = $validatePermissionModifyApply;
        $this->sendMessageToSecretariat = $sendMessageToSecretariat;
        $this->retrieveScreenLocks = $retrieveScreenLocks;
    }

    /**
     * confirmConditions
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function confirmConditions(int $applyId): void
    {
        if (!$this->validatePermissionModifyApply->__invoke($applyId)) {
            abort(403);
        }
    }

    /**
     * sendMessageToSecretariat
     *
     * @param SendMessageToSecretariatParameter|null $notifyParameter
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function sendMessageToSecretariat(?SendMessageToSecretariatParameter $notifyParameter): void
    {
        if (!is_null($notifyParameter)) {
            $this->sendMessageToSecretariat->__invoke($notifyParameter);
        }
    }

    /**
     * isSectionLocked
     *
     * @see Ncc01\Apply\Enterprise\Classification\ScreenLocks
     * @param int $applyId
     * @param string $sectionName ScreenLocksのキー
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function isSectionLocked(int $applyId, string $sectionName): bool
    {
        $screenLocks = $this->retrieveScreenLocks->__invoke($applyId);
        return isset($screenLocks[$sectionName]) && $screenLocks[$sectionName] === true;
    }
}
