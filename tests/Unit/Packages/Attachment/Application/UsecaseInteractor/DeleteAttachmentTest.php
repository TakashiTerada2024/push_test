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

namespace Tests\Unit\Packages\Attachment\Application\UsecaseInteractor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Attachment\Application\GatewayInterface\FileStorageInterface;
use Ncc01\Attachment\Application\Usecase\DeleteAttachmentInterface;
use Ncc01\Attachment\Application\UsecaseInteractor\DeleteAttachment;
use Ncc01\Attachment\Enterprise\Entity\Attachment;
use Tests\TestCase;

/**
 * DeleteAttachmentTest
 *
 * @package Tests\Unit\Packages\Attachment\Application\UsecaseInteractor
 * @coversDefaultClass \Ncc01\Attachment\Application\UsecaseInteractor\DeleteAttachment
 */
class DeleteAttachmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test___construct
     * @covers ::__construct
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test___construct()
    {
        $targetClass = App::make(DeleteAttachment::class);
        $this->assertInstanceOf(DeleteAttachmentInterface::class, $targetClass);
    }

    /**
     * test___invoke
     * @covers ::__invoke
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test___invoke()
    {
        //argument
        $attachmentId = 999;

        //dummy result
        $dummyFilePath = 'TEST-FILE-PATH';

        //repositoryのMock
        $repositoryMock = $this->partialMock(
            AttachmentRepositoryInterface::class,
            function (MockInterface $mock) use ($attachmentId, $dummyFilePath) {
                //repositoryのfind()
                $attachment = new Attachment();
                $attachment->setFilePath($dummyFilePath);

                $mock->shouldReceive('find')
                    ->with($attachmentId)
                    ->once()
                    ->andReturn($attachment);

                //repositoryのdelete()
                $mock->shouldReceive('delete')->with($attachmentId)->once();
            }
        );

        //FileStorageのMock
        $fileStorageMock = $this->partialMock(
            FileStorageInterface::class,
            function (MockInterface $mock) use ($attachmentId, $dummyFilePath) {
                //repositoryのfind()が返却する結果と同じパスを受け取るはず
                $mock->shouldReceive('delete')->with($dummyFilePath)->once();
            }
        );

        $targetClass = new DeleteAttachment($fileStorageMock, $repositoryMock);
        $targetClass->__invoke($attachmentId);
    }
}
