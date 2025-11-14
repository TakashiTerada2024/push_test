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

use App\Http\RequestTranslator\FileToSaveParameter;
use App\Rules\SmallIntMax;
use App\Rules\UploadMaxFileSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection03ParameterInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * SaveSection03Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection03Request extends FormRequest
{
    use CreateSaveAttachmentParameterTrait;
    use CreateMessageToSecretariatParameterTrait;

    private FileToSaveParameter $fileToSaveParameter;
    private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser;


    /**
     * SaveSection02Request constructor.
     * @param FileToSaveParameter $fileToSaveParameter
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        FileToSaveParameter $fileToSaveParameter,
        RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
        $this->fileToSaveParameter = $fileToSaveParameter;
        $this->retrieveAuthenticatedUser = $retrieveAuthenticatedUser;
    }

    /**
     * rules
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function rules(): array
    {
        $membersRule = $this->membersRules();

        return
            $membersRule + [
                'attachment301' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment302' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment303' => ['nullable', 'file', new UploadMaxFileSize()],
                '3_number_of_users' => ['nullable', 'integer', new SmallIntMax()],
                '10_applicant_birthday' => ['nullable', 'date', 'after:25 Dec 1926', 'before:-18 years'],
            ];
    }

    public function createSaveParameter(): SaveApplySection03ParameterInterface
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        /** @var SaveApplySection03ParameterInterface $parameter */
        $parameter = App::make(SaveApplySection03ParameterInterface::class);

        $parameter = $this->setApplicantInfoToSaveParameter($parameter);

        $parameter->setAttachment301(
            $this->createSaveAttachmentParameter(
                301,
                $authenticatedUser->getId(),
                $this->getUploadedFileFromInput('attachment301')
            )
        );

        $parameter->setAttachment302(
            $this->createSaveAttachmentParameter(
                302,
                $authenticatedUser->getId(),
                $this->getUploadedFileFromInput('attachment302')
            )
        );

        $parameter->setAttachment303(
            $this->createSaveAttachmentParameter(
                303,
                $authenticatedUser->getId(),
                $this->getUploadedFileFromInput('attachment303')
            )
        );

        $parameter->setNumberOfUsers($this->input('3_number_of_users'));
        $parameter->setArrayUsers($this->input('apply_users'));
        return $parameter;
    }

    public function messages(): array
    {
        return [
            'apply_users.*.name.required' => '必須項目です。',
            'apply_users.*.institution.required' => '必須項目です',
            'apply_users.*.position.required' => '必須項目です',
            'apply_users.*.role.required' => '必須項目です',
            '10_applicant_birthday.before' => '18歳以上の方のみ登録可能です',
            '10_applicant_birthday.after' => '昭和生まれ以降の方のみ登録可能です',
        ];
    }

    private function setApplicantInfoToSaveParameter(
        SaveApplySection03ParameterInterface $parameter
    ): SaveApplySection03ParameterInterface {
        $applicantType = $this->input('10_applicant_type');

        $parameter->setApplicantType($applicantType);

        //2:法人 選択の場合
        if ((string)$applicantType === '2') {
            $parameter->setApplicantName($this->input('10_applicant_name_2'));
            $parameter->setApplicantAddress($this->input('10_applicant_address_2'));
            $parameter->setAffiliation($this->input('affiliation_2'));
            //法人には誕生日入力欄が存在しない。nullで更新する。
            $parameter->setApplicantBirthday(null);
            return $parameter;
        }

        //デフォルト（1.個人、または未選択状態の場合）
        $parameter->setApplicantName($this->input('10_applicant_name_1'));
        $parameter->setApplicantAddress($this->input('10_applicant_address_1'));
        $parameter->setApplicantBirthday($this->input('10_applicant_birthday_1'));
        $parameter->setAffiliation($this->input('affiliation_1'));

        return $parameter;
    }

    private function membersRules(): array
    {
        $number = $this->input('3_number_of_users');

        $i = 0;
        $membersRule = [];
        while ($i < $number) {
            $membersRule = $membersRule + [
                    'apply_users.' . (string)$i . '.name' => ['required'],
                    'apply_users.' . (string)$i . '.institution' => ['required'],
                    'apply_users.' . (string)$i . '.position' => ['required'],
                    'apply_users.' . (string)$i . '.role' => ['required'],
                ];
            $i++;
        }
        return $membersRule;
    }
}
