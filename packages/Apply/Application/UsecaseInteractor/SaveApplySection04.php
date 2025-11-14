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

namespace Ncc01\Apply\Application\UsecaseInteractor;

use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection04ParameterInterface;
use Ncc01\Apply\Application\Usecase\SaveApplySection04Interface;

/**
 * SaveApplySection04
 *
 * @package Ncc01\Apply\Application\Usecase
 */
class SaveApplySection04 implements SaveApplySection04Interface
{
    /** @var ApplyRepositoryInterface $applyRepository */
    private $applyRepository;

    /**
     * SaveApplySection04 constructor.
     * @param ApplyRepositoryInterface $applyRepository
     */
    public function __construct(ApplyRepositoryInterface $applyRepository)
    {
        $this->applyRepository = $applyRepository;
    }

    /**
     * __invoke
     *
     * @param SaveApplySection04ParameterInterface $parameter
     * @param int $applyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function __invoke(SaveApplySection04ParameterInterface $parameter, int $applyId): void
    {
        $arrayParameter = [
            '4_year_of_diagnose_start' => $parameter->getYearOfDiagnoseStart(),
            '4_year_of_diagnose_end' => $parameter->getYearOfDiagnoseEnd(),
            '4_area_prefectures' => $parameter->getAreaPrefectures(),
            '4_idc_type' => $parameter->getIdcType(),
            '4_idc_detail' => $parameter->getIdcDetail(),
            '4_is_alive_required' => $parameter->getIsAliveRequired(),
            '4_is_alive_date_required' => $parameter->getIsAliveDateRequired(),
            '4_is_cause_of_death_required' => $parameter->getIsCauseOfDeathRequired(),
            '4_sex' => $parameter->getSex(),
            '4_sex_detail' => $parameter->getSexDetail(),
            '4_range_of_age_type' => $parameter->getRangeOfAgeType(),
            '4_range_of_age_detail' => $parameter->getRangeOfAgeDetail(),
        ];

        $this->applyRepository->update($arrayParameter, $applyId);
    }
}
