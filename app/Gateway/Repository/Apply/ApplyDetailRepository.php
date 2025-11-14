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

namespace App\Gateway\Repository\Apply;

use App\Models\Apply;
use App\Query\RetrieveChangeHistoriesOfApply;
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\Gateway\ApplyDetailRepositoryInterface;
use Ncc01\Apply\Application\Service\Builder\ApplyDetailBuilder;
use Ncc01\Apply\Enterprise\Entity\ApplyDetail;
use Ncc01\Attachment\Application\QueryServiceInterface\RetrieveChangeHistoriesOfApplyInterface;

/**
 * ApplyDetailRepository
 *
 * @package App\Gateway\Repository\Apply
 */
class ApplyDetailRepository implements ApplyDetailRepositoryInterface
{
    /**
     * findById
     *
     * @param int $applyId
     * @return ApplyDetail
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) 問題なし
     */
    public function findById(int $applyId): ApplyDetail
    {
        $model = Apply::findOrFail($applyId);

        $copiedApplies = $this->retrieveChangeHistoriesOfApply($applyId);

        /** @var ApplyDetailBuilder $builder */
        $builder = App::make(ApplyDetailBuilder::class);
        $builder->setId($model->id)
            ->setUserId($model->user_id)
            ->setType($model->type_id)
            ->setStatus($model->status)
            ->setSubject($model->subject)
            ->setAffiliation($model->affiliation)
            ->setDepartment($model->department)
            ->setSummary($model->summary)
            ->setSubmittedAt($model->submitted_at);

        $builder->setPurposeOfUse($model->{'2_purpose_of_use'})
            ->setNeedToUse($model->{'2_need_to_use'})
            ->setEthicalReviewStatus($model->{'2_ethical_review_status'})
            ->setEthicalReviewRemark($model->{'2_ethical_review_remark'})
            ->setEthicalReviewBoardName($model->{'2_ethical_review_board_name'})
            ->setEthicalReviewBoardCode($model->{'2_ethical_review_board_code'})
            ->setEthicalReviewBoardDate($model->{'2_ethical_review_board_date'});

        $builder->setNumberOfUsers($model->{'3_number_of_users'});
        $builder->setYearOfDiagnoseStart($model->{'4_year_of_diagnose_start'})
            ->setYearOfDiagnoseEnd($model->{'4_year_of_diagnose_end'})
            ->setAreaPrefectures($model->{'4_area_prefectures'})
            ->setIdcType($model->{'4_idc_type'})
            ->setIdcDetail($model->{'4_idc_detail'})
            ->setIsAliveRequired($model->{'4_is_alive_required'})
            ->setIsAliveDateRequired($model->{'4_is_alive_date_required'})
            ->setIsCauseOfDeathRequired($model->{'4_is_cause_of_death_required'})
            ->setSex($model->{'4_sex'})
            ->setSexDetail($model->{'4_sex_detail'})
            ->setRangeOfAgeType($model->{'4_range_of_age_type'})
            ->setRangeOfAgeDetail($model->{'4_range_of_age_detail'});

        $builder->setResearchMethod($model->{'5_research_method'});

        $builder->setUsagePeriodEnd($model->{'6_usage_period_end'})
            ->setResearchPeriodStart($model->{'6_research_period_start'})
            ->setResearchPeriodEnd($model->{'6_research_period_end'});

        $builder->setScheduledToBeAnnounced($model->{'8_scheduled_to_be_announced'});

        $builder->setTreatmentAfterUse($model->{'9_treatment_after_use'});

        $builder->setClerkName($model->{'10_clerk_name'})
            ->setClerkContactAddress($model->{'10_clerk_contact_address'})
            ->setClerkContactEmail($model->{'10_clerk_contact_email'})
            ->setClerkContactPhoneNumber($model->{'10_clerk_contact_phone_number'})
            ->setClerkContactExtensionPhoneNumber($model->{'10_clerk_contact_extension_phone_number'})
            ->setApplicantType($model->{'10_applicant_type'})
            ->setApplicantName($model->{'10_applicant_name'})
            ->setApplicantAddress($model->{'10_applicant_address'})
            ->setApplicantBirthday($model->{'10_applicant_birthday'})
            ->setApplicantNameKana($model->{'10_applicant_name_kana'})
            ->setApplicantPhoneNumber($model->{'10_applicant_phone_number'})
            ->setApplicantExtensionPhoneNumber($model->{'10_applicant_extension_phone_number'})
            ->setRemark($model->{'10_remark'});

        $builder->setCopiedApplies($copiedApplies);

        return $builder->__invoke();
    }

    private function retrieveChangeHistoriesOfApply($applyId): ?iterable
    {
        $retrieveChangeHistoriesOfApply = App::make(RetrieveChangeHistoriesOfApplyInterface::class);
        return $retrieveChangeHistoriesOfApply->__invoke($applyId);
    }
}
