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
use Ncc01\Apply\Application\InputBoundary\SaveApplySection02ParameterInterface;
use Ncc01\Apply\Application\InputData\SaveApplySection02Parameter;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use App\Rules\UploadMaxFileSize;

/**
 * SaveSection02Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection02Request extends FormRequest
{
    use CreateSaveAttachmentParameterTrait;
    use CreateMessageToSecretariatParameterTrait;

    /** @var FileToSaveParameter $fileToSaveParameter */
    private $fileToSaveParameter;
    /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
    private $retrieveAuthenticatedUser;

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
        return [
                'attachment201' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment202' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment203' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment204' => ['nullable', 'file', new UploadMaxFileSize()],
                'attachment205' => ['nullable', 'file', new UploadMaxFileSize()],
        ];
    }

    /**
     * createParameter
     *
     * @return SaveApplySection02ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createParameter(): SaveApplySection02ParameterInterface
    {
        $parameter = new SaveApplySection02Parameter();

        //入力フォーム内容を保存用パラメタに詰める
        $parameter = $this->addFormParameters($parameter);
        $parameter = $this->addSaveFilesParameter($parameter);
        return $parameter;
    }

    /**
     * addFormParameters
     *
     * @param SaveApplySection02ParameterInterface $parameter
     * @return SaveApplySection02ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function addFormParameters(
        SaveApplySection02ParameterInterface $parameter
    ): SaveApplySection02ParameterInterface {
        $parameter->setPurposeOfUse($this->input('2_purpose_of_use'));
        $parameter->setNeedToUse($this->input('2_need_to_use'));
        $parameter->setEthicalReviewStatus($this->input('2_ethical_review_status'));
        $parameter->setEthicalReviewRemark($this->input('2_ethical_review_remark'));
        $parameter->setEthicalReviewBoardName($this->input('2_ethical_review_board_name'));
        $parameter->setEthicalReviewBoardCode($this->input('2_ethical_review_board_code'));
        $parameter->setEthicalReviewBoardDate($this->input('2_ethical_review_board_date'));
        return $parameter;
    }

    /**
     * addSaveFilesParameter
     *
     * @param SaveApplySection02ParameterInterface $parameter
     * @return SaveApplySection02ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function addSaveFilesParameter(
        SaveApplySection02ParameterInterface $parameter
    ): SaveApplySection02ParameterInterface {

        $a201Parameter = $this->fileParameter(201);
        $parameter->setAttachment201($a201Parameter);

        $a202Parameter = $this->fileParameter(202);
        $parameter->setAttachment202($a202Parameter);

        $a203Parameter = $this->fileParameter(203);
        $parameter->setAttachment203($a203Parameter);

        $a204Parameter = $this->fileParameter(204);
        $parameter->setAttachment204($a204Parameter);

        $a205Parameter = $this->fileParameter(205);
        $parameter->setAttachment205($a205Parameter);

        return $parameter;
    }

    /**
     * fileParameter
     *
     * @param $applyId
     * @param $attachmentTypeId
     * @return \Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function fileParameter($attachmentTypeId)
    {
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();

        return $this->createSaveAttachmentParameter(
            $attachmentTypeId,
            $authenticatedUser->getId(),
            $this->getUploadedFileFromInput('attachment' . $attachmentTypeId)
        );
    }
}
