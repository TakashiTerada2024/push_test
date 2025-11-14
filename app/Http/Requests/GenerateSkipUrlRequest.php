<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 申出スキップURL生成リクエスト
 */
class GenerateSkipUrlRequest extends FormRequest
{
    /**
     * リクエストの認可を判定
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * バリデーションルールを取得
     *
     * @return array
     */
    public function rules()
    {
        return [
            'apply_type_id' => 'required|integer|min:1|max:4',
        ];
    }

    /**
     * 認証失敗時のメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'apply_type_id.required' => '申出種別IDは必須です',
            'apply_type_id.integer' => '申出種別IDは整数である必要があります',
            'apply_type_id.min' => '申出種別IDは1以上である必要があります',
            'apply_type_id.max' => '申出種別IDは4以下である必要があります',
        ];
    }
}
