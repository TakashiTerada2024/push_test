<?php

namespace App\Http\Requests\Apply\Lock;

use Illuminate\Foundation\Http\FormRequest;
use Ncc01\Apply\Application\InputBoundary\SaveAttachmentLocksParameterInterface;
use Ncc01\Apply\Application\InputData\SaveAttachmentLocksParameter;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;

class SaveAttachmentLocksRequest extends FormRequest
{
    public function __construct(private AttachmentTypes $attachmentTypes)
    {
        parent::__construct();
        $this->attachmentTypes = $attachmentTypes;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 認可チェックはコントローラで行うため、ここではtrueを返す
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        return [
            // 'attachment_locks' => 'array|nullable',
            // 'attachment_locks.*' => 'string|in:true',
        ];
    }

    /**
     * バリデーションエラーのカスタムメッセージ
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'attachment_locks.array' => '添付資料ロックの形式が不正です。',
            'attachment_locks.*.in' => '無効な値が含まれています。',
        ];
    }

    /**
     * 添付資料ロックの配列を取得
     *
     * @return array
     */
    public function attachmentLocks(): array
    {
        $result = [];
        $ids = $this->attachmentTypes->listOfId()->all();
        foreach ($ids as $id) {
            $result[$id] = $this->input("attachment_locks.{$id}") === 'true';
        }
        return $result;
    }

    /**
     * 添付資料ロック保存のパラメータを作成
     *
     * @return SaveAttachmentLocksParameterInterface
     */
    public function createSaveAttachmentLocksParameter(): SaveAttachmentLocksParameterInterface
    {
        return new SaveAttachmentLocksParameter(
            $this->attachmentLocks()
        );
    }
}
