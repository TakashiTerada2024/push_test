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

namespace Ncc01\User\Application\UsecaseInteractor;

use LogicException;
use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\User\Application\Service\ValidatePermission;
use Ncc01\User\Application\Usecase\ValidatePermissionSubmitAttachmentInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionParameters\ValidateSubmitAttachmentParameter;

/**
 * ValidatePermissionSubmitAttachment
 *
 * @package Ncc01\User\Application\UsecaseInteractor
 */
class ValidatePermissionSubmitAttachment implements ValidatePermissionSubmitAttachmentInterface
{
    /**
     * __construct
     *
     * @param ValidatePermission $validatePermissionService
     * @param ValidateSubmitAttachmentParameter $validateSubmitAttachmentParameter
     * @param AttachmentRepositoryInterface $attachmentRepository
     * @param ApplyRepositoryInterface $applyRepository
     * @return void
     */
    public function __construct(
        private ValidatePermission $validatePermissionService,
        private ValidateSubmitAttachmentParameter $validateSubmitAttachmentParameter,
        private AttachmentRepositoryInterface $attachmentRepository,
        private ApplyRepositoryInterface $applyRepository
    ) {
    }

    /**
     * __invoke
     *
     * @param int $applyId
     * @param int $attachmentId
     * @return bool
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function __invoke(int $applyId, int $attachmentId): bool
    {
        $attachment = $this->attachmentRepository->find($attachmentId);
        $apply = $this->applyRepository->findById($attachment->getApplyId());

        $this->validateSubmitAttachmentParameter->setAttachment($attachment);
        $this->validateSubmitAttachmentParameter->setApply($apply);

        return $this->validatePermissionService->__invoke($this->validateSubmitAttachmentParameter);
    }
}
