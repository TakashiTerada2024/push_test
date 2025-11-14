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

namespace Ncc01\User\Application\Usecase\ValidatePermissionParameters;

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\User\Application\Service\ValidatePermissionParameterInterface;
use Ncc01\User\Enterprise\Spec\Permission\ShowAttachmentBySecretariat;
use Ncc01\User\Enterprise\Spec\Permission\PermissionSpecInterface;
use Ncc01\User\Enterprise\User;

/**
 * ValidateShowAttachmentBySecretariatParameter
 * 事務局送付資料の閲覧権限があるかを確認するために必要な情報を保持するパラメタクラス
 *
 * @package Ncc01\User\Application\Input\Permission
 */
class ValidateShowAttachmentBySecretariatParameter implements ValidatePermissionParameterInterface
{
    /** @var int $applyId 申出ID */
    private $applyId;
    /** @var ApplyRepositoryInterface $applyRepository */
    private $applyRepository;

    /**
     * ValidateModifyApplyParameter constructor.
     * @param ApplyRepositoryInterface $applyRepository
     */
    public function __construct(ApplyRepositoryInterface $applyRepository)
    {
        $this->applyRepository = $applyRepository;
    }

    /**
     * getPermissionSpec
     *
     * @param User $loginUser
     * @return PermissionSpecInterface
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function getPermissionSpec(User $loginUser): PermissionSpecInterface
    {
        $apply = $this->applyRepository->findById($this->applyId);
        return new ShowAttachmentBySecretariat(
            loginUserRole: $loginUser->getRole(),
            loginUserId: $loginUser->getId(),
            applyUserId: $apply->getApplicant()->getId(),
        );
    }

    /**
     * @param int $applyId
     */
    public function setApplyId(int $applyId): void
    {
        $this->applyId = $applyId;
    }
}
