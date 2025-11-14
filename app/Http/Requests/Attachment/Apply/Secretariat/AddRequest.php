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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Http\Requests\Attachment\Apply\Secretariat;

use App\Http\RequestTranslator\FileToSaveParameter;
use App\Rules\UploadMaxFileSize;
use App\Rules\UploadMaxNumberOfFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

/**
 * AddRequest
 *
 * @package App\Http\Requests\Attachment\Apply\Secretariat
 */
class AddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'new' => ['nullable', new UploadMaxFileSize(), new UploadMaxNumberOfFile()],
        ];
    }

    /**
     * createSaveAttachmentParameter
     *
     * @return array|null
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function createSaveAttachmentParameter(): ?array
    {
        $parameters = [];

        $files = $this->getNewFileList();
        if (is_null($files)) {
            return null;
        }

        /** @var FileToSaveParameter $fileToSaveParameter */
        $fileToSaveParameter = App::make(FileToSaveParameter::class);
        /** @var RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser */
        $retrieveAuthenticatedUser = App::make(RetrieveAuthenticatedUserInterface::class);

        foreach ($files as $file) {
            if (!$this->isNewFile($file)) {
                return null;
            }

            $parameter = $fileToSaveParameter->__invoke($file);
            $authenticatedUser = $retrieveAuthenticatedUser->__invoke();
            $parameter->setUserId($authenticatedUser->getId());
            $parameters[] = $parameter;
        }

        return $parameters;
    }

    /**
     * getNewFileList
     *
     * @return mixed array|null
     * @author m.shomura <m.shomura@balocco.info>
     */
    protected function getNewFileList(): mixed
    {
        return $this->file('new');
    }

    /**
     * isNewFile
     *
     * @return bool
     * @author m.shomura <m.shomura@balocco.info>
     */
    protected function isNewFile($file): bool
    {
        return is_a($file, UploadedFile::class);
    }
}
