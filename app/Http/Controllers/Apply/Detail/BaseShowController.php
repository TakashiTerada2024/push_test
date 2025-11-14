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
use LogicException;
use Ncc01\Apply\Application\Gateway\RetrieveViewPathInterface;
use Ncc01\Apply\Application\Usecase\RetrieveApplyBaseInfoInterface;
use Ncc01\Apply\Application\Usecase\RetrieveScreenLocksInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionShowApplyInterface;

/**
 * BaseController
 *
 * @codeCoverageIgnore コントローラーの抽象クラスは計測対象から除外
 * @package App\Http\Controllers\Apply\Detail
 */
abstract class BaseShowController extends Controller
{
    private RetrieveViewPathInterface $retrieveViewPath;
    private ValidatePermissionShowApplyInterface $validatePermissionShowApply;
    private RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo;
    private RetrieveScreenLocksInterface $retrieveScreenLocks;

    public function __construct(
        RetrieveApplyBaseInfoInterface $retrieveApplyBaseInfo,
        RetrieveViewPathInterface $retrieveViewPath,
        ValidatePermissionShowApplyInterface $validatePermissionShowApply,
        RetrieveScreenLocksInterface $retrieveScreenLocks
    ) {
        $this->retrieveViewPath = $retrieveViewPath;
        $this->validatePermissionShowApply = $validatePermissionShowApply;
        $this->retrieveApplyBaseInfo = $retrieveApplyBaseInfo;
        $this->retrieveScreenLocks = $retrieveScreenLocks;
    }

    /**
     * buildViewPath
     *
     * @param int $applyId
     * @param string $viewName
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function buildViewPath(int $applyId, string $viewName)
    {
        $applyTypeId = $this->retrieveApplyBaseInfo->__invoke($applyId)->getTypeId();
        if (is_null($applyTypeId)) {
            throw new LogicException('Apply type is null.');
        }
        return $this->retrieveViewPath->__invoke($applyTypeId) . '.' . $viewName;
    }

    /**
     * confirmConditions
     *
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function confirmConditions(int $applyId): void
    {
        if (!$this->validatePermissionShowApply->__invoke($applyId)) {
            abort(404);
        }

        $baseInfo = $this->retrieveApplyBaseInfo->__invoke($applyId);

        $applyTypeId = $baseInfo->getTypeId();
        if (is_null($applyTypeId)) {
            abort(400, '申出の区分が決定されていないため、表示できません。');
        }

        if ($baseInfo->isPriorConsultation()) {
            abort(403, '事前相談中のため、表示できません。');
        }
    }

    /**
     * getScreenLocks
     *
     * @param int $applyId
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    protected function getScreenLocks(int $applyId): array
    {
        return $this->retrieveScreenLocks->__invoke($applyId);
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
        $screenLocks = $this->getScreenLocks($applyId);
        return isset($screenLocks[$sectionName]) && $screenLocks[$sectionName] === true;
    }
}
