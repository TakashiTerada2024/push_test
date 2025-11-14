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

namespace App\Http\Requests\Apply;

use Illuminate\Foundation\Http\FormRequest;
use Ncc01\Apply\Enterprise\Classification\ApplyTypes;

/**
 * GenerateSkipUrlRequest
 * 申出スキップURL生成リクエスト
 *
 * @package App\Http\Requests\Apply
 */
class GenerateSkipUrlRequest extends FormRequest
{
    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'apply_type_id' => ['required', 'integer', 'min:1', 'max:4'],
        ];
    }

    /**
     * バリデーションメッセージ
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'apply_type_id.required' => '申出種別が選択されていません。',
            'apply_type_id.integer' => '申出種別の値が不正です。',
            'apply_type_id.min' => '申出種別の値が不正です。',
            'apply_type_id.max' => '申出種別の値が不正です。',
        ];
    }

    /**
     * リクエストの許可判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 事務局ユーザーかどうかを確認
        return $this->user() && $this->user()->isSecretariat();
    }

    /**
     * 申出種別IDを取得
     *
     * @return int
     */
    public function getApplyTypeId(): int
    {
        return (int)$this->input('apply_type_id');
    }

    /**
     * 作成者IDを取得
     *
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->user()->id;
    }

    /**
     * 申出種別の名称を取得
     *
     * @return string
     */
    public function getApplyTypeName(): string
    {
        $applyTypes = new ApplyTypes();
        return $applyTypes->valueOfName($this->getApplyTypeId());
    }
}
