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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Attachment\Application\GatewayInterface\AttachmentRepositoryInterface;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;

/**
 * UpdateAttachments20230829
 */
class UpdateAttachments20230829 extends Seeder
{
    /**
     * UpdateAttachments20230829 constructor.
     *
     * @param ApplyRepositoryInterface $applyRepository
     * @param AttachmentRepositoryInterface $attachmentRepository
     */
    public function __construct(
        private ApplyRepositoryInterface $applyRepository,
        private AttachmentRepositoryInterface $attachmentRepository
    ) {
    }

    /**
     * run
     *
     * @return void
     */
    public function run()
    {
        $this->updateSubmitting();
        $this->updateApproved();
        $this->updateUploaded();
    }

    /**
     * updateSubmitting
     * 以下申出ステータスを「提出済み」に更新
     * ・申出文書 確認中
     *
     * @return void
     */
    private function updateSubmitting(): void
    {
        $applies = $this->fetchApplyByStatus([
            ApplyStatuses::CHECKING_DOCUMENT
        ]);

        $attachments = $this->attachmentRepository
            ->findByApplyIds($applies)
            ->where('status', null)
            ->get();
        foreach ($attachments as $attachment) {
            $this->attachmentRepository->update(['status' => 2], $attachment->id);
        }
    }

    /**
     * updateApproved
     * 以下申出ステータスを「承認済み」に更新
     * ・申出文書 提出中
     * ・審査中
     * ・応諾
     *
     * @return void
     */
    private function updateApproved(): void
    {
        $applies = $this->fetchApplyByStatus([
            ApplyStatuses::SUBMITTING_DOCUMENT,
            ApplyStatuses::UNDER_REVIEW,
            ApplyStatuses::ACCEPTED
        ]);

        $attachments = $this->attachmentRepository
            ->findByApplyIds($applies)
            ->where('status', null)
            ->get();
        foreach ($attachments as $attachment) {
            $this->attachmentRepository->update(['status' => 3], $attachment->id);
        }
    }

    /**
     * updateUploaded
     * 以下申出ステータスを「アップロード済み」に更新
     * ・申出文書 相談中
     * ・申出文書 作成中
     * ・中止
     *
     * @return void
     */
    private function updateUploaded(): void
    {
        $applies = $this->fetchApplyByStatus([
            ApplyStatuses::PRIOR_CONSULTATION,
            ApplyStatuses::CREATING_DOCUMENT,
            ApplyStatuses::CANCEL
        ]);

        $attachments = $this->attachmentRepository
            ->findByApplyIds($applies)
            ->where('status', null)
            ->get();
        foreach ($attachments as $attachment) {
            $this->attachmentRepository->update(['status' => 1], $attachment->id);
        }
    }

    /**
     * fetchApplyByStatus
     *
     * @param array $applyStatusList
     * @return array
     */
    private function fetchApplyByStatus(array $applyStatusList): Array
    {
        return $this->applyRepository->findIdsByStatus($applyStatusList);
    }
}
