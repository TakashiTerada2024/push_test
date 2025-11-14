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
use Ncc01\Apply\Application\InputBoundary\SaveApplySection10ParameterInterface;

/**
 * SaveSection04Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection10Request extends FormRequest
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
        return [];
    }

    public function createSaveParameter(): SaveApplySection10ParameterInterface
    {
        /** @var SaveApplySection10ParameterInterface $parameter */
        $parameter = App::make(SaveApplySection10ParameterInterface::class);

        $parameter->setClerkName($this->input('10_clerk_name'));
        $parameter->setClerkContactAddress($this->input('10_clerk_contact_address'));
        $parameter->setClerkContactEmail($this->input('10_clerk_contact_email'));
        $parameter->setClerkContactPhoneNumber($this->input('10_clerk_contact_phone_number'));
        $parameter->setClerkContactExtensionPhoneNumber($this->input('10_clerk_contact_extension_phone_number'));

        $parameter->setRemark($this->input('10_remark'));

        return $parameter;
    }
}
