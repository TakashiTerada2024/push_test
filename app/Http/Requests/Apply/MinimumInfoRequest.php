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

class MinimumInfoRequest extends FormRequest
{
    /**
     * カスタムバリデーションエラーメッセージ
     */
    public const ERROR_APPLY_TYPE_MISMATCH = '申出種別がスキップURLの情報と一致しません。';
    public const ERROR_SKIP_URL_USED = 'このスキップURLは既に使用されています。';
    public const ERROR_SKIP_URL_EXPIRED = 'このスキップURLの有効期限が切れています。';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 一時的に全ユーザーに許可（テスト用）
        return true;

        // 申出者だけが利用可能
        // return $this->user() && $this->user()->role_id === 3;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'apply_type_id' => ['required', 'integer', 'min:1', 'max:4'],
            'research_purpose' => ['required', 'string', 'max:1000'],
            'research_method' => ['required', 'string', 'max:1000'],
            'need_to_use' => ['required', 'string', 'max:1000'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_name_kana' => ['required', 'string', 'max:255'],
            'contact_affiliation' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_extension' => ['nullable', 'string', 'max:20'],
            'skip_url_id' => ['nullable', 'integer', 'exists:application_skip_urls,id'],
        ];
    }

    /**
     * バリデーションメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'subject.required' => '研究課題名を入力してください。',
            'apply_type_id.required' => '申出種別を選択してください。',
            'apply_type_id.integer' => '申出種別の値が不正です。',
            'apply_type_id.min' => '申出種別の値が不正です。',
            'apply_type_id.max' => '申出種別の値が不正です。',
            'research_purpose.required' => '調査研究の目的を入力してください。',
            'research_method.required' => '調査研究の方法を入力してください。',
            'need_to_use.required' => '全国がん登録情報の必要性を入力してください。',
            'contact_name.required' => '連絡先の名前を入力してください。',
            'contact_name_kana.required' => '連絡先の名前（カナ）を入力してください。',
            'contact_affiliation.required' => '連絡先の所属を入力してください。',
            'contact_phone.required' => '連絡先の電話番号を入力してください。',
            'skip_url_id.integer' => 'スキップURLの値が不正です。',
            'skip_url_id.exists' => '指定されたスキップURLが存在しません。',
        ];
    }

    /**
     * パラメータをまとめた配列を返す
     *
     * @return array
     */
    public function getParameters()
    {
        return [
            'subject' => $this->input('subject'),
            'apply_type_id' => (int)$this->input('apply_type_id'),
            'research_purpose' => $this->input('research_purpose'),
            'research_method' => $this->input('research_method'),
            'need_to_use' => $this->input('need_to_use'),
            'contact_name' => $this->input('contact_name'),
            'contact_name_kana' => $this->input('contact_name_kana'),
            'contact_affiliation' => $this->input('contact_affiliation'),
            'contact_phone' => $this->input('contact_phone'),
            'contact_extension' => $this->input('contact_extension'),
            'skip_url_id' => $this->input('skip_url_id') ? (int)$this->input('skip_url_id') : null,
        ];
    }

    /**
     * 申出種別の名称を取得
     *
     * @return string
     */
    public function getApplyTypeName()
    {
        $applyTypes = new ApplyTypes();
        return $applyTypes->valueOfName($this->input('apply_type_id'));
    }

    /**
     * バリデーターの設定
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateSkipUrl($validator);
        });
    }

    /**
     * スキップURLの検証を行う
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    private function validateSkipUrl($validator)
    {
        $skipUrlId = $this->input('skip_url_id');
        if (!$skipUrlId) {
            return;
        }

        $skipUrl = $this->getSkipUrl($skipUrlId);
        if (!$skipUrl) {
            return;
        }

        $this->validateApplyTypeMatch($validator, $skipUrl);
        $this->validateSkipUrlUsage($validator, $skipUrl);
        $this->validateSkipUrlExpiration($validator, $skipUrl);
    }

    /**
     * スキップURLを取得する
     *
     * @param int $skipUrlId
     * @return \App\Models\ApplicationSkipUrl|null
     */
    private function getSkipUrl($skipUrlId)
    {
        return \App\Models\ApplicationSkipUrl::find($skipUrlId);
    }

    /**
     * 申出種別の整合性を検証する
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param \App\Models\ApplicationSkipUrl $skipUrl
     * @return void
     */
    private function validateApplyTypeMatch($validator, $skipUrl)
    {
        if ($skipUrl->apply_type_id != $this->input('apply_type_id')) {
            $validator->errors()->add(
                'apply_type_id',
                self::ERROR_APPLY_TYPE_MISMATCH
            );
        }
    }

    /**
     * スキップURLの使用状態を検証する
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param \App\Models\ApplicationSkipUrl $skipUrl
     * @return void
     */
    private function validateSkipUrlUsage($validator, $skipUrl)
    {
        if ($skipUrl->is_used) {
            $validator->errors()->add(
                'skip_url_id',
                self::ERROR_SKIP_URL_USED
            );
        }
    }

    /**
     * スキップURLの有効期限を検証する
     *
     * @param \Illuminate\Validation\Validator $validator
     * @param \App\Models\ApplicationSkipUrl $skipUrl
     * @return void
     */
    private function validateSkipUrlExpiration($validator, $skipUrl)
    {
        if ($skipUrl->expired_at && $skipUrl->expired_at < now()) {
            $validator->errors()->add(
                'skip_url_id',
                self::ERROR_SKIP_URL_EXPIRED
            );
        }
    }
}
