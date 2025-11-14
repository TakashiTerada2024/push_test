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

namespace App\Http\Requests\Apply\Start;

use App\Http\Requests\Apply\MessageBodyDto;
use App\Rules\OnlyCtypeDigit;
use App\Rules\OnlyZenkakuKana;
use App\Rules\SmallTextMax;
use App\Rules\TextMax;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\InputBoundary\CreateApplyParameterInterface;

/**
 * SaveRequest
 *
 * @package App\Http\Requests\Apply\Start
 */
class SaveRequest extends FormRequest
{
    /**
     * rules
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function rules(): array
    {
        return [
            'type_id' => ['required', 'in:1,2,3,4,99'],
            'subject' => ['required', 'string', App::make(SmallTextMax::class)],
            '6_research_period_start' => [
                'nullable',
                'date',
                'before:6_research_period_end'
            ],
            '6_research_period_end' => [
                'nullable',
                'date',
                'after:6_research_period_start'
            ],
            '2_purpose_of_use' => ['required', 'string', App::make(TextMax::class)],
            '5_research_method' => ['required', 'string', App::make(TextMax::class)],
            '2_need_to_use' => ['required', 'string', App::make(TextMax::class)],
            '10_applicant_name' => ['required', 'string', App::make(SmallTextMax::class)],
            '10_applicant_name_kana' => [
                'required',
                'string',
                App::make(OnlyZenkakuKana::class),
                App::make(SmallTextMax::class)
            ],
            'affiliation' => ['required', 'string', App::make(SmallTextMax::class)],
            '10_applicant_phone_number' => [
                'required',
                'string',
                App::make(SmallTextMax::class),
                App::make(OnlyCtypeDigit::class)
            ],
            '10_applicant_extension_phone_number' => ['nullable', 'string', App::make(SmallTextMax::class)],
        ];
    }

    public function createParameter(): CreateApplyParameterInterface
    {
        /** @var CreateApplyParameterInterface $parameter */
        $parameter = App::make(CreateApplyParameterInterface::class);
        $parameter->setSubject($this->input('subject'));
        $parameter->setResearchPeriodStart($this->input('6_research_period_start'));
        $parameter->setResearchPeriodEnd($this->input('6_research_period_end'));
        $parameter->setPurposeOfUse($this->input('2_purpose_of_use'));
        $parameter->setResearchMethod($this->input('5_research_method'));
        $parameter->setNeedToUse($this->input('2_need_to_use'));
        $parameter->setQuestionAtPriorConsultation($this->input('question_at_prior_consultation'));

        $parameter->setAffiliation($this->input('affiliation'));
        $parameter->setApplicantName($this->input('10_applicant_name'));
        $parameter->setApplicantNameKana($this->input('10_applicant_name_kana'));
        $parameter->setApplicantPhoneNumber($this->input('10_applicant_phone_number'));
        $parameter->setApplicantExtensionPhoneNumber($this->input('10_applicant_extension_phone_number'));

        $parameter->setApplyType($this->applyType());

        return $parameter;
    }

    public function createMessageDto(): MessageBodyDto
    {
        $dto = new MessageBodyDto();
        $dto->setApplyType($this->input('type_id'));
        $dto->setSubject($this->input('subject'));
        $dto->setResearchPeriodStart($this->input('6_research_period_start'));
        $dto->setResearchPeriodEnd($this->input('6_research_period_end'));
        $dto->setPurposeOfUse($this->input('2_purpose_of_use'));
        $dto->setResearchMethod($this->input('5_research_method'));
        $dto->setNeedToUse($this->input('2_need_to_use'));
        $dto->setApplicantName($this->input('10_applicant_name'));
        $dto->setApplicantNameKana($this->input('10_applicant_name_kana'));
        $dto->setAffiliation($this->input('affiliation'));
        $dto->setApplicantPhoneNumber($this->input('10_applicant_phone_number'));
        $dto->setApplicantExtensionPhoneNumber($this->input('10_applicant_extension_phone_number'));
        $dto->setRemark($this->input('question_at_prior_consultation'));
        return $dto;
    }

    private function applyType(): ?int
    {
        if ($this->input('type_id') === '99') {
            return null;
        }
        return (int)$this->input('type_id');
    }
}
