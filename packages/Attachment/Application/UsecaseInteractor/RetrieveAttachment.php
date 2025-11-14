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

namespace Ncc01\Attachment\Application\UsecaseInteractor;

use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Attachment\Application\GatewayInterface\FileStorageInterface;
use Ncc01\Attachment\Application\OutputBoundary\RetrieveAttachmentOutputInterface;
use Ncc01\Attachment\Application\OutputData\RetrieveAttachmentOutput;
use Ncc01\Attachment\Application\Usecase\RetrieveAttachmentInterface;

/**
 * RetrieveAttachment
 *
 * @package Ncc01\Attachment\Application\Usecase
 */
class RetrieveAttachment implements RetrieveAttachmentInterface
{
    /** @var AttachmentRepositoryInterface $attachmentRepository */
    private $attachmentRepository;
    /** @var FileStorageInterface $fileStorage */
    private $fileStorage;

    /**
     * RetrieveAttachment constructor.
     * @param AttachmentRepositoryInterface $attachmentRepository
     * @param FileStorageInterface $fileStorage
     */
    public function __construct(
        AttachmentRepositoryInterface $attachmentRepository,
        FileStorageInterface $fileStorage
    ) {
        $this->attachmentRepository = $attachmentRepository;
        $this->fileStorage = $fileStorage;
    }

    /**
     * __invoke
     *
     * @param int $attachmentId 添付ファイルID
     * @return RetrieveAttachmentOutputInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(int $attachmentId): RetrieveAttachmentOutputInterface
    {
        //添付ファイルEntity取得
        $entity = $this->attachmentRepository->find($attachmentId);
        //パスを変換
        $fullPath = $this->fileStorage->path($entity->getFilePath());

        return new RetrieveAttachmentOutput(
            $entity->getApplyId(),
            $fullPath,
            $entity->getName()
        );
    }
}
