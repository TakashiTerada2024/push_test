<?php

namespace App\Http\Requests\Apply\Lock;

use Illuminate\Foundation\Http\FormRequest;
use Ncc01\Apply\Application\InputBoundary\SaveScreenLocksParameterInterface;
use Ncc01\Apply\Application\InputData\SaveScreenLocksParameter;
use Ncc01\Apply\Enterprise\Classification\ScreenLocks;

class SaveScreenLocksRequest extends FormRequest
{
    public function __construct(private ScreenLocks $screenLocks)
    {
        parent::__construct();
        $this->screenLocks = $screenLocks;
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
            'screen_locks' => 'array|nullable',
            'screen_locks.*' => 'string|in:true',
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
            'screen_locks.array' => '画面ロックの形式が不正です。',
            'screen_locks.*.in' => '無効な値が含まれています。',
        ];
    }

    /**
     * 画面ロックの配列を取得
     *
     * @return array
     */
    public function screenLocks(): array
    {

        $result = [];
        foreach ($this->screenLocks->keys() as $code) {
            $result[$code] = $this->input("screen_locks.{$code}") === 'true';
        }
        return $result;
    }

    /**
     * 画面ロック保存のパラメータを作成
     *
     * @return SaveScreenLocksParameterInterface
     */
    public function createSaveScreenLocksParameter(): SaveScreenLocksParameterInterface
    {
        return new SaveScreenLocksParameter(
            $this->screenLocks()
        );
    }
}
