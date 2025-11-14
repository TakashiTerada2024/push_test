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

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Tests\Feature\FeatureTestBase;

/**
 * 添付ファイル機能に関する機能テスト
 */
class AttachmentTest extends FeatureTestBase
{
    /**
     * test_addAttachmentInDatabase
     * 添付ファイルの追加により、データベースに保存が行われることのテスト
     * ※実際にファイルがアップロードされるかがテストできない（対象ファイル名が特定できないため・・・）
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_addAttachmentInDatabase()
    {
        Storage::fake('local');

        //事前準備、テスト用の申請者と申出を作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => ApplyStatuses::CREATING_DOCUMENT, //作成中ステータス
        ])->create();


        //まず保存して、保存が成功することを確認
        $dummyFilename = uniqid() . '-dummy.jpg';
        $this->actingAs($applicantOwner)
            ->post(
                '/attachment/apply/add/' . $targetApply->id,
                //アップロードのパラメータ
                [
                    'new' => UploadedFile::fake()->image($dummyFilename)
                ]
            );

        //データベースに正常に保存されることの確認
        $this->assertDatabaseHas('attachments', [
            'apply_id' => $targetApply->id,
            'name' => $dummyFilename
        ]);
    }

    /**
     * test_deleteAttachmentInDatabase
     * 添付ファイルの削除処理により、データベースからファイルが削除されていることの確認
     *
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_deleteAttachmentInDatabase()
    {
        //事前準備、テスト用の申請者と申出を作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        //テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
            'status' => ApplyStatuses::CREATING_DOCUMENT, //作成中ステータス
        ])->create();

        //添付ファイルデータの作成
        $attachment = Attachment::factory()->state([
            'apply_id' => $targetApply->id,
            'user_id' => $applicantOwner->id,
            'status' => AttachmentStatuses::UPLOADED,
        ])->create();

        $route = route('attachment.apply.delete', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);

        $this->actingAs($applicantOwner)->get($route);

        //データベースから削除されていることを検証
        $this->assertDatabaseMissing('attachments', [
            'id' => $attachment->id,
        ]);
    }

    /**
     * test_submitAttachmentInDatabase
     * 添付ファイルの提出機能により、データベースのステータスが変更されている確認
     *
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_submitAttachmentInDatabase()
    {
        // テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::UPLOADED,
        ])->create();

        $route = route('attachment.apply.submit', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $this->actingAs($applicantOwner)->get($route);

        // データベースのステータスが変更されている確認
        $this->assertDatabaseHas('attachments', [
            'id' => $attachment->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::SUBMITTING
        ]);
    }

    /**
     * test_cancelAttachmentInDatabase
     * 添付ファイルの承認機能により、データベースのステータスが変更されている確認
     *
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_cancelAttachmentInDatabase()
    {
        // テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 3])->create();

        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        $route = route('attachment.apply.cancel', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $this->actingAs($applicantOwner)->get($route);

        // データベースのステータスが変更されている確認
        $this->assertDatabaseHas('attachments', [
            'id' => $attachment->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::UPLOADED
        ]);
    }

    /**
     * test_approveAttachmentInDatabase
     * 添付ファイルの承認機能により、データベースのステータスが変更されている確認
     *
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_approveAttachmentInDatabase()
    {
        // テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 2])->create();

        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::SUBMITTING,
        ])->create();

        $route = route('attachment.apply.approve', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $this->actingAs($applicantOwner)->get($route);

        // データベースのステータスが変更されている確認
        $this->assertDatabaseHas('attachments', [
            'id' => $attachment->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::APPROVED
        ]);
    }

    /**
     * test_disapproveAttachmentInDatabase
     * 添付ファイルの承認取消機能により、データベースのステータスが変更されている確認
     *
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_disapproveAttachmentInDatabase()
    {
        // テスト用申出者の作成
        $applicantOwner = User::factory()->state(['role_id' => 2])->create();

        // テスト用申請の作成
        $targetApply = Apply::factory()->state([
            'user_id' => $applicantOwner->id,
        ])->create();

        $attachment = Attachment::factory()->state([
            'user_id' => $applicantOwner->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::APPROVED,
        ])->create();

        $route = route('attachment.apply.disapprove', [
            'applyId' => $targetApply->id,
            'id' => $attachment->id
        ]);
        $this->actingAs($applicantOwner)->get($route);

        // データベースのステータスが変更されている確認
        $this->assertDatabaseHas('attachments', [
            'id' => $attachment->id,
            'apply_id' => $targetApply->id,
            'status' => AttachmentStatuses::UPLOADED
        ]);
    }
}
