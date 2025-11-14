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

namespace Ncc01\Apply\Enterprise\Spec\CheckingDocument;

use Illuminate\Support\Facades\App;
use Ncc01\Apply\Enterprise\Classification\AttachmentStatuses;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Apply\Enterprise\Entity\ApplyDetail;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section01\Section01ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section02\Section02ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section03\Section03ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section04\Section04ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section05\Section05ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section06\Section06ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section07\Section07ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section08\Section08ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section09\Section09ValidationSpec;
use Ncc01\Apply\Enterprise\Spec\CheckingDocument\Items\Section10\Section10ValidationSpec;
use Specification\Validation\ValidationSpecCollection;

/**
 * Spec
 *
 * @package Ncc01\Apply\Enterprise\Spec\CheckingDocument2
 */
class Spec extends ValidationSpecCollection
{
    public function __construct(
        private ApplyDetail $applyDetail,
        private array $latestAttachments,
        private array $applyUsers
    ) {
        $this->customMessages = [
            "notEmpty" => '必須項目です',
            "stringType" => '文字列での記入のみ可能です',
            "zenkakuKana" => '全角カナのみ利用可能です',
            "dateTime" => '日時形式での入力のみ可能です',
            "intType" => '数値での記入のみ可能です',
            "in" => '許可された選択肢以外の値が記入されています',
        ];
    }

    public function getSpecKey(): string
    {
        return 'GovernmentLinkageValidationSpec2';
    }

    public function getSpecName(): string
    {
        return '行政関係者/リンケージ 申出書式2';
    }

    protected function initCollection()
    {
        $this->section01();
        $this->section02();
        $this->section03();
        $this->section04();
        $this->section05();
        $this->section06();
        $this->section07();
        $this->section08();
        $this->section09();
        $this->section10();
    }

    private function section01(): void
    {
        $this->add(
            new Section01ValidationSpec(
                $this->applyDetail->getType()?->getId(),
                ($this->extractAttachmentIdByConditions(101)),
                ($this->extractAttachmentIdByConditions(102)),
                ($this->extractAttachmentIdByConditions(103)),
            )
        );
    }

    private function section02()
    {
        $this->add(
            new Section02ValidationSpec(
                $this->applyDetail->getPurposeOfUse(),
                $this->applyDetail->getNeedToUse(),
                $this->applyDetail->getType()?->getId(),
                $this->applyDetail->getEthicalReviewStatus(),
                $this->applyDetail->getEthicalReviewRemark(),
                $this->applyDetail->getEthicalReviewBoardName(),
                $this->applyDetail->getEthicalReviewBoardCode(),
                $this->applyDetail->getEthicalReviewBoardDate(),
                ($this->extractAttachmentIdByConditions(201)),
                ($this->extractAttachmentIdByConditions(202)),
                ($this->extractAttachmentIdByConditions(203)),
                ($this->extractAttachmentIdByConditions(204)),
                ($this->extractAttachmentIdByConditions(205))
            )
        );
    }

    private function section03(): void
    {
        $this->add(
            new Section03ValidationSpec(
                $this->applyDetail->getType()?->getId(),
                ($this->extractAttachmentIdByConditions(301)),
                ($this->extractAttachmentIdByConditions(302)),
                $this->applyUsers,
                $this->applyDetail->getNumberOfUsers(),
                $this->applyDetail->getApplicantName(),
                $this->applyDetail->getApplicantNameKana(),
                $this->applyDetail->getApplicantType(),
                $this->applyDetail->getApplicantBirthday(),
                $this->applyDetail->getApplicantAddress(),
                $this->applyDetail->getAffiliation()
            )
        );
    }

    private function section04(): void
    {
        $this->add(
            new Section04ValidationSpec(
                $this->applyDetail->getYearOfDiagnoseStart(),
                $this->applyDetail->getYearOfDiagnoseEnd(),
                $this->applyDetail->getAreaPrefectures(),
                $this->applyDetail->getIdcType(),
                $this->applyDetail->getIdcDetail(),
                $this->applyDetail->getIsAliveRequired(),
                $this->applyDetail->getIsAliveDateRequired(),
                $this->applyDetail->getIsCauseOfDeathRequired(),
                $this->applyDetail->getSex(),
                $this->applyDetail->getRangeOfAgeType(),
                $this->applyDetail->getRangeOfAgeDetail(),
                $this->applyDetail->getType(),
            )
        );
    }

    private function section05(): void
    {
        $this->add(
            new
            Section05ValidationSpec(
                $this->applyDetail->getResearchMethod(),
                $this->applyDetail->getType()?->getId(),
                ($this->extractAttachmentIdByConditions(501)),
                ($this->extractAttachmentIdByConditions(502)),
            )
        );
    }

    private function section06(): void
    {
        $this->add(
            new Section06ValidationSpec(
                $this->applyDetail->getUsagePeriodEnd(),
                $this->applyDetail->getResearchPeriodStart(),
                $this->applyDetail->getResearchPeriodEnd()
            )
        );
    }

    private function section07(): void
    {
        $this->add(
            new Section07ValidationSpec(
                ($this->extractAttachmentIdByConditions(701)),
                $this->applyDetail->getType()
            )
        );
    }

    private function section08(): void
    {
        $this->add(
            new Section08ValidationSpec($this->applyDetail->getScheduledToBeAnnounced())
        );
    }

    private function section09(): void
    {
        $this->add(new Section09ValidationSpec($this->applyDetail->getTreatmentAfterUse()));
    }

    private function section10(): void
    {
        $this->add(
            new Section10ValidationSpec(
                $this->applyDetail->getClerkName(),
                $this->applyDetail->getClerkContactAddress(),
                $this->applyDetail->getClerkContactEmail(),
                $this->applyDetail->getClerkContactPhoneNumber()
            )
        );
    }

    /**
     * extractAttachmentIdByConditions
     * 条件に当てはまるattachmentIDまたはnullを返却
     *
     * @param int $attachmentTypeId
     * @return int|null
     */
    private function extractAttachmentIdByConditions(int $attachmentTypeId): ?int
    {
        if (!isset($this->latestAttachments[$attachmentTypeId])) {
            return null;
        }

        return $this->extractSubmitting($attachmentTypeId);
    }

    /**
     * extractSubmitting
     * 「提出済」以降のステータスになっている添付ファイルID取得
     *
     * @param int $attachmentTypeId
     * @return int|null
     */
    private function extractSubmitting(int $attachmentTypeId): ?int
    {
        foreach ($this->latestAttachments[$attachmentTypeId] as $attachment) {
            if (
                in_array(
                    $attachment['status'],
                    [AttachmentStatuses::SUBMITTING, AttachmentStatuses::APPROVED],
                    true
                )
            ) {
                return isset($attachment['id']) ? $attachment['id'] : null;
            }
        }
        return null;
    }
}
