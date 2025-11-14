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

use Carbon\Carbon;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Attachment\Application\GatewayInterface\FileStorageInterface;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Ncc01\Common\Application\GatewayInterface\UuidCreatorInterface;

/**
 * SaveAttachments
 *
 * @package Ncc01\Attachment\Application\Usecase
 */
class SaveAttachment implements SaveAttachmentInterface
{
    /** @var FileStorageInterface $fileStorage */
    private $fileStorage;
    /** @var AttachmentRepositoryInterface $attachmentRepository */
    private $attachmentRepository;
    /** @var UuidCreatorInterface $uuidCreator */
    private $uuidCreator;

    /**
     * SaveAttachment constructor.
     * @param FileStorageInterface $fileStorage
     * @param AttachmentRepositoryInterface $attachmentRepository
     * @param UuidCreatorInterface $uuidCreator
     */
    public function __construct(
        FileStorageInterface $fileStorage,
        AttachmentRepositoryInterface $attachmentRepository,
        UuidCreatorInterface $uuidCreator
    ) {
        $this->fileStorage = $fileStorage;
        $this->attachmentRepository = $attachmentRepository;
        $this->uuidCreator = $uuidCreator;
    }

    /**
     * __invoke
     *
     * @param SaveAttachmentParameterInterface $parameter
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveAttachmentParameterInterface $parameter): int
    {
        //1.ファイルをストレージ上に保存する
        $path = $this->createFilePath($parameter);
        $this->fileStorage->put($path, $parameter->getContent());

        //2.保存したファイル内容をデータベースに登録する
        return $this->attachmentRepository->create($parameter, $path);
    }

    /**
     * createFilePath
     * ファイルパスの生成。所有者ID、申出IDごとにディレクトリを分けた上で先頭に日時文字列を付加する。
     *
     * @param SaveAttachmentParameterInterface $parameter
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function createFilePath(SaveAttachmentParameterInterface $parameter)
    {
        $tmpArray = [];
        $tmpArray[] = $parameter->getUserId();
        $tmpArray[] = $parameter->getApplyId();

        $tmpArray[] = Carbon::now()->format('Ymd-His') . '-' . $this->uuidCreator->create();

        return implode(DIRECTORY_SEPARATOR, $tmpArray);
    }
}
