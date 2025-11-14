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
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\InputBoundary\SaveApplySection05ParameterInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use App\Rules\UploadMaxFileSize;

/**
 * SaveSection04Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection05Request extends FormRequest
{
    use CreateSaveAttachmentParameterTrait;
    use CreateMessageToSecretariatParameterTrait;

    /** @var FileToSaveParameter $fileToSaveParameter */
    private $fileToSaveParameter;
    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

    /**
     * SaveSection05Request constructor.
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
        return [
            'attachment501' => ['nullable', 'file', new UploadMaxFileSize()],
            'attachment502' => ['nullable', 'file', new UploadMaxFileSize()],
        ];
    }

    /**
     * createSaveParameter
     *
     * @return SaveApplySection05ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createSaveParameter(): SaveApplySection05ParameterInterface
    {
        $user = $this->retrieveAuthenticatedUser->__invoke();

        /** @var SaveApplySection05ParameterInterface $parameter */
        $parameter = App::make(SaveApplySection05ParameterInterface::class);
        $parameter->setResearchMethod($this->input('5_research_method'));

        $parameter->setAttachment501(
            $this->createSaveAttachmentParameter(501, $user->getId(), $this->getUploadedFileFromInput('attachment501'))
        );

        $parameter->setAttachment502(
            $this->createSaveAttachmentParameter(502, $user->getId(), $this->getUploadedFileFromInput('attachment502'))
        );

        return $parameter;
    }
}
