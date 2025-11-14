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
use Ncc01\User\Application\Usecase\ValidatePermissionDeleteAttachmentInterface;
use Ncc01\User\Application\Usecase\ValidatePermissionParameters\ValidateDeleteAttachmentParameter;

/**
 * ValidatePermissionDeleteAttachment
 *
 * @package Ncc01\User\Application\UsecaseInteractor
 */
class ValidatePermissionDeleteAttachment implements ValidatePermissionDeleteAttachmentInterface
{
    /**
     * __construct
     *
     * @param ValidatePermission $validatePermissionService
     * @param ValidateDeleteAttachmentParameter $validateDeleteAttachmentParameter
     * @param AttachmentRepositoryInterface $attachmentRepository
     * @param ApplyRepositoryInterface $applyRepository
     * @return void
     */
    public function __construct(
        private ValidatePermission $validatePermissionService,
        private ValidateDeleteAttachmentParameter $validateDeleteAttachmentParameter,
        private AttachmentRepositoryInterface $attachmentRepository,
        private ApplyRepositoryInterface $applyRepository
    ) {
    }

    /**
     * __invoke
     *
     * @param int|null $applyId attachmentIdが事前に決定できていない状況では、applyIdを与える
     * @param int|null $attachmentId attachmentIdが事前に決定できている状況では、attachmentId を与える
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(?int $applyId = null, ?int $attachmentId = null): bool
    {
        $this->checkArguments($applyId, $attachmentId);

        if (!is_null($attachmentId)) {
            return $this->withAttachmentId($attachmentId);
        }
        //checkArgumentsにより保証されているため、nullではない
        assert(!is_null($applyId));
        return $this->withApplyId($applyId);
    }

    /**
     * checkArguments
     * URLパラメータの検査。$applyId と $attachmentId のXORを保証するための検査処理
     * @param int|null $applyId
     * @param int|null $attachmentId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function checkArguments(?int $applyId, ?int $attachmentId): void
    {
        if (!is_null($applyId) && !is_null($attachmentId)) {
            throw new LogicException('Both applyId and attachmentId cannot be specified as arguments.');
        }

        if (is_null($applyId) && is_null($attachmentId)) {
            throw new LogicException('Specify either applyId or attachmentId as an argument.');
        }
    }

    /**
     * withAttachmentId
     * attachmentId が与えられている場合の判定処理
     * @param int $attachmentId
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function withAttachmentId(int $attachmentId): bool
    {
        $attachment = $this->attachmentRepository->find($attachmentId);
        $apply = $this->applyRepository->findById($attachment->getApplyId());

        $this->validateDeleteAttachmentParameter->setAttachment($attachment);
        $this->validateDeleteAttachmentParameter->setApply($apply);

        return $this->validatePermissionService->__invoke($this->validateDeleteAttachmentParameter);
    }

    /**
     * withApplyId
     * applyIdが与えられている場合の判定処理
     * @param int $applyId
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function withApplyId(int $applyId): bool
    {
        $apply = $this->applyRepository->findById($applyId);

        $this->validateDeleteAttachmentParameter->setAttachment(null);
        $this->validateDeleteAttachmentParameter->setApply($apply);

        return $this->validatePermissionService->__invoke($this->validateDeleteAttachmentParameter);
    }
}
