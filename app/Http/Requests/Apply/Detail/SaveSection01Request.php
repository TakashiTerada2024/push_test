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
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection01ParameterInterface;
use Ncc01\Apply\Application\InputData\SaveApplySection01Parameter;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * SaveSection01Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection01Request extends FormRequest
{
    use CreateSaveAttachmentParameterTrait;
    use CreateMessageToSecretariatParameterTrait;

    /** @var FileToSaveParameter $fileToSaveParameter */
    private $fileToSaveParameter;
    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

    /**
     * SaveSection01Request constructor.
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
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function rules(): array
    {
        $maxSize = Config::get('app-ncc01.upload-max-filesize');

        return [
            'attachment101' => ['nullable', 'file', 'max:' . $maxSize],
            'attachment102' => ['nullable', 'file', 'max:' . $maxSize],
            'attachment103' => ['nullable', 'file', 'max:' . $maxSize],
        ];
    }


    /**
     * createParameter
     *
     * @return SaveApplySection01ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameter(): SaveApplySection01ParameterInterface
    {
        $saveApplyParameter = new SaveApplySection01Parameter();
        $user = $this->retrieveAuthenticatedUser->__invoke();

        $saveApplyParameter->setAttachment101(
            $this->createSaveAttachmentParameter(101, $user->getId(), $this->getUploadedFileFromInput('attachment101'))
        );
        $saveApplyParameter->setAttachment102(
            $this->createSaveAttachmentParameter(102, $user->getId(), $this->getUploadedFileFromInput('attachment102'))
        );
        $saveApplyParameter->setAttachment103(
            $this->createSaveAttachmentParameter(103, $user->getId(), $this->getUploadedFileFromInput('attachment103'))
        );

        return $saveApplyParameter;
    }
}
