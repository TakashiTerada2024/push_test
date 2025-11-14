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

namespace App\Http\Requests\Attachment\Apply;

use App\Http\RequestTranslator\FileToSaveParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Ncc01\Attachment\Application\InputBoundary\SaveAttachmentParameterInterface;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * AddRequest
 *
 * @package App\Http\Requests\Attachment\Apply
 */
class AddRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    /**
     * createSaveAttachmentParameter
     *
     * @return SaveAttachmentParameterInterface|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function createSaveAttachmentParameter(): ?SaveAttachmentParameterInterface
    {
        $file = $this->getNewFile();
        if (!$file) {
            return null;
        }
        /** @var FileToSaveParameter $fileToSaveParameter */
        $fileToSaveParameter = App::make(FileToSaveParameter::class);
        /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
        $retrieveAuthenticatedUser = App::make(RetrieveAuthenticatedUserInterface::class);

        $parameter = $fileToSaveParameter->__invoke($file);
        $authenticatedUser = $retrieveAuthenticatedUser->__invoke();
        $parameter->setUserId($authenticatedUser->getId());

        return $parameter;
    }

    /**
     * getNewFile
     *
     * @return UploadedFile|null
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function getNewFile(): ?UploadedFile
    {
        $file = $this->file('new');
        assert(is_a($file, UploadedFile::class) || is_null($file));
        return $file;
    }
}
