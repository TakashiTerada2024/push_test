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

namespace Tests\Feature\Http\Controllers\Attachment;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Tests\Feature\FeatureTestBase;

/**
 * Class DownloadControllerTest
 * @package Tests\Feature\Http\Controllers\Attachment
 * @see \InsertAttachmentsForTesting1
 */
class DownloadControllerTest extends FeatureTestBase
{
    use DatabaseTransactions;

    /**
     * test_ApplicantCanDownloadFile
     * 申請者101が作成した添付資料のダウンロードを、申請者101本人が行う
     * @covers \App\Http\Controllers\Attachment\DownloadController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_ApplicantCanDownloadFile()
    {
        //申請者であるユーザ101
        $ownerActor = User::findOrFail(101);
        $attachmentId = $this->createAttachment(1, 101);

        //ログインしている申請者本人の資料であるため、ダウンロード可能となるはずである。
        $response = $this->actingAs($ownerActor)->get('/attachment/download/' . $attachmentId);
        $response->assertDownload();
    }

    /**
     * 事務局ユーザ102が作成し申請1に添付した資料を、申請1の申請者101がダウンロードする
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_ApplicantCanDownloadMadeBySecretariat()
    {
        //Actor:申請1の申請者であるユーザ101
        $ownerActor = User::findOrFail(101);

        //添付資料データの作成
        $attachmentId = $this->createAttachment(1, 102);

        //この添付資料は事務局ユーザが作成し、申請:1 に紐づいている。
        //申請1の申請者であるユーザ101は所有者とみなされるため、ダウンロード可能。
        $response = $this->actingAs($ownerActor)->get('/attachment/download/' . $attachmentId);
        $response->assertDownload();
    }


    /**
     * test_SecretariatCanDownloadFile
     *
     * @covers \App\Http\Controllers\Attachment\DownloadController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_SecretariatCanDownloadFile()
    {
        //窓口組織アカウントであるユーザ102
        $ownerActor = User::find(102);

        //添付資料データの作成
        $attachmentId1 = $this->createAttachment(1, 101);
        $attachmentId2 = $this->createAttachment(1, 102);

        $response = $this->actingAs($ownerActor)->get('/attachment/download/' . $attachmentId1);
        $response->assertDownload();

        $response = $this->actingAs($ownerActor)->get('/attachment/download/' . $attachmentId2);
        $response->assertDownload();
    }


    /**
     * test_OtherUserCanNotDownloadFile
     *
     * @covers \App\Http\Controllers\Attachment\DownloadController
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_OtherUserCanNotDownloadFile()
    {
        $ownerActor = User::find(103);
        $attachmentIds = [];

        $attachmentIds[] = $this->createAttachment(1, 101);
        $attachmentIds[] = $this->createAttachment(1, 102);

        foreach ($attachmentIds as $attachmentId) {
            $response = $this->actingAs($ownerActor)->get('/attachment/download/' . $attachmentId);
            $response->assertStatus(403);
        }
    }

    /**
     * createAttachment
     * テストに必要な添付資料の作成を行う
     * @param int $applyId
     * @param int $userId
     * @return int
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function createAttachment(int $applyId, int $userId): int
    {
        /** @var SaveAttachmentInterface $saveAttachment */
        $saveAttachment = App::make(SaveAttachmentInterface::class);

        /** @var SaveAttachmentParameterInterface $saveAttachmentParameter */
        $saveAttachmentParameter = App::make(SaveAttachmentParameterInterface::class);
        $saveAttachmentParameter->setApplyId($applyId);
        $saveAttachmentParameter->setUserId($userId);
        $saveAttachmentParameter->setClientOriginalName('テスト資料.txt');
        $saveAttachmentParameter->setContent('dummy');
        //添付資料を作成し、IDを発番して返却
        return $saveAttachment->__invoke($saveAttachmentParameter);
    }
}
