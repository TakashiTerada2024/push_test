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
use Ncc01\Apply\Application\InputBoundary\SaveApplySection07ParameterInterface;
use Ncc01\Apply\Application\InputData\SaveApplySection07Parameter;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;
use Illuminate\Http\UploadedFile;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;

/**
 * SaveSection04Request
 *
 * @package App\Http\Requests\Apply\Detail
 */
class SaveSection07Request extends FormRequest
{
    use CreateSaveAttachmentParameterTrait;
    use CreateMessageToSecretariatParameterTrait;

    public function __construct(
        private FileToSaveParameter $fileToSaveParameter,
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
    }

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


    /**
     * createSaveParameter
     *
     * @return SaveApplySection07ParameterInterface
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createSaveParameter(): SaveApplySection07ParameterInterface
    {
        $parameter = new SaveApplySection07Parameter();
        $authenticatedUser = $this->retrieveAuthenticatedUser->__invoke();
        $attachment701 = [];
        $files = $this->file('attachments701') ?? [];
        $activeAttachment701Id = array_filter($this->input('active_attachements701') ?? []);    //残すファイルid

        foreach ($files as $key => $file) {
            $tmpParameter = $this->createSaveAttachmentParameter(701, $authenticatedUser->getId(), $file);
            if ($tmpParameter) {
                $this->setData($key, $tmpParameter, $attachment701, $activeAttachment701Id);
            }
        }

        $parameter->setAttachments701($attachment701);
        $parameter->setActivateAttachments701Id($activeAttachment701Id);

        return $parameter;
    }

    /**
     * setData
     *
     * @param int $key
     * @param SaveAttachmentParameterInterface|null $parameter
     * @param list<SaveAttachmentParameterInterface> $attachment701
     * @param list<int> $activeAttachment701Id
     */
    private function setData(
        int $key,
        SaveAttachmentParameterInterface|null $parameter,
        array &$attachment701,
        array &$activeAttachment701Id
    ) {
        $attachment701[$key] = $parameter;   //新たなファイル
        if (isset($activeAttachment701Id[$key])) {
            unset($activeAttachment701Id[$key]);  //残すファイルからidを削除
        }
    }
}
