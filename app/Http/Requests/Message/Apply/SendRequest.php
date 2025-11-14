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

namespace App\Http\Requests\Message\Apply;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Ncc01\Messaging\Application\InputData\SendMessageToSecretariatParameter;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;

/**
 * SendRequest
 *
 * @package App\Http\Requests\Message\Apply
 */
class SendRequest extends FormRequest
{
    /**
     * rules
     *
     * @return \string[][]
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function rules(): array
    {
        return [
            'message_body' => ['required']
        ];
    }

    /**
     * authorize
     *
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * messages
     *
     * @return array
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * getBody
     *
     * @return string
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getBody(): string
    {
        return $this->input('message_body');
    }

    /**
     * getNotificationId
     *
     * @return string|null
     * @author ushiro <k.ushiro@balocco.info>
     */
    public function getNotificationId(): ?string
    {
        return $this->input('notification_id');
    }

    /**
     * createParameterSendToSecretariat
     *
     * @return SendMessageToSecretariatParameter
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameterSendToSecretariat(): SendMessageToSecretariatParameter
    {
        /** @var SendMessageToSecretariatParameter $parameter */
        $parameter = App::make(SendMessageToSecretariatParameter::class);
        $parameter->setMessageBody($this->getBody());
        return $parameter;
    }

    /**
     * createParameter
     *
     * @return SendCommonMessageParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameterSendToApplicant(): SendCommonMessageParameterInterface
    {
        /** @var SendCommonMessageParameterInterface $parameter */
        $parameter = App::make(SendCommonMessageParameterInterface::class);
        $parameter->setMessageBody($this->getBody());
        return $parameter;
    }
}
