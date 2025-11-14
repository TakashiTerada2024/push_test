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

namespace App\Http\Requests\Apply\Detail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use LogicException;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection04ParameterInterface;
use Ncc01\Apply\Application\InputData\SaveApplySection04Parameter;
use Ncc01\Common\Enterprise\Classification\Prefectures;

/**
 * SaveSection04Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection04Request extends FormRequest
{
    use CreateMessageToSecretariatParameterTrait;

    /**
     * rules
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function rules(): array
    {
        return [
//            '4_is_alive_required' => ['required', 'integer', 'min:5']
        ];
    }

    /**
     * createSaveParameter
     *
     * @return SaveApplySection04ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createSaveParameter(): SaveApplySection04ParameterInterface
    {
        $parameter = new SaveApplySection04Parameter();
        $parameter->setYearOfDiagnoseStart($this->input('4_year_of_diagnose_start'));
        $parameter->setYearOfDiagnoseEnd($this->input('4_year_of_diagnose_end'));
        $parameter->setAreaPrefectures($this->areaPrefectures());
        $parameter->setIdcType($this->input('4_idc_type'));
        $parameter->setIdcDetail($this->input('4_idc_detail'));
        $parameter->setIsAliveRequired($this->input('4_is_alive_required'));
        $parameter->setIsAliveDateRequired($this->input('4_is_alive_date_required'));
        $parameter->setIsCauseOfDeathRequired($this->input('4_is_cause_of_death_required'));
        $parameter->setSex($this->input('4_sex'));
        $parameter->setSexDetail($this->input('4_sex_detail'));
        $parameter->setRangeOfAgeType($this->input('4_range_of_age_type'));
        $parameter->setRangeOfAgeDetail($this->input('4_range_of_age_detail'));

        return $parameter;
    }

    /**
     * areaPrefectures
     *
     * @return int[]|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function areaPrefectures(): ?array
    {
        //2:地域指定が選択されている場合、選択された都道府県
        return $this->castPrefectures();
    }

    /**
     * castPrefectures
     *
     * @return int[]|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function castPrefectures(): ?array
    {
        $inputPrefectures = $this->input('4_area_prefectures');

        if (is_null($inputPrefectures)) {
            return null;
        }

        return array_map(
            function ($item) {
                return (int)$item;
            },
            $inputPrefectures
        );
    }
}
