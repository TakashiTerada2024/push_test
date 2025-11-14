<?php

namespace App\Services\Apply;

use App\Models\Apply;
use App\Models\ApplicationSkipUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use InvalidArgumentException;

/**
 * 申出作成サービス
 */
class ApplyCreateService
{
    /**
     * 最低限必要な情報から申出を作成する
     *
     * @param array $parameters
     * @return int 作成された申出のID
     * @throws \InvalidArgumentException パラメータが不正な場合
     */
    public function createApplyFromMinimumInfo(array $parameters): int
    {
        // 必須パラメータの検証
        $this->validateParameters($parameters);

        // 申出を作成
        $apply = new Apply();
        $apply->user_id = Auth::id();
        $apply->type_id = $parameters['apply_type_id'];
        $apply->subject = $parameters['subject'];
        $apply->status = ApplyStatuses::CREATING_DOCUMENT; // 申出文書作成中ステータス(2)

        // 連絡先情報を設定
        $apply->{'10_applicant_name'} = $parameters['contact_name'];  // 正しいカラム名に修正
        $apply->affiliation = $parameters['contact_affiliation'];

        // 追加情報は後からセクションごとに更新できるので、
        // ここではmemoフィールドは使わず、基本情報のみを保存

        // 保存して作成されたIDを返す
        $apply->save();

        // スキップURLがある場合は使用済みに更新
        if (!empty($parameters['skip_url_id'])) {
            $this->markSkipUrlAsUsed($parameters['skip_url_id']);
        }

        return $apply->id;
    }

    /**
     * パラメータの検証
     *
     * @param array $parameters 検証するパラメータ
     * @throws InvalidArgumentException パラメータが不正な場合
     * @return void
     */
    private function validateParameters(array $parameters): void
    {
        $requiredFields = [
            'subject',
            'apply_type_id',
            'research_purpose',
            'research_method',
            'need_to_use',
            'contact_name',
            'contact_name_kana',
            'contact_affiliation',
            'contact_phone',
        ];

        foreach ($requiredFields as $field) {
            if (empty($parameters[$field])) {
                throw new InvalidArgumentException("Required parameter '$field' is missing or empty");
            }
        }

        // 申出種別IDの値が1〜4の範囲内であることを確認
        //TODO:なぜ確認するかが不明である。申出種別IDは、1～4しか存在しないため、下記分岐の意図が不明。条件も複雑である
        if (
            !is_int($parameters['apply_type_id']) ||
            $parameters['apply_type_id'] < 1 ||
            $parameters['apply_type_id'] > 4
        ) {
            throw new InvalidArgumentException('Invalid apply_type_id value');
        }
    }

    /**
     * スキップURLを使用済みに更新する
     *
     * @param int $skipUrlId スキップURLのID
     * @return void
     */
    private function markSkipUrlAsUsed(int $skipUrlId): void
    {
        try {
            $skipUrl = ApplicationSkipUrl::findOrFail($skipUrlId);
            $skipUrl->is_used = true;
            $skipUrl->save();

            Log::info('Skip URL marked as used', [
                'skip_url_id' => $skipUrlId,
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to mark skip URL as used', [
                'skip_url_id' => $skipUrlId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            // 処理は継続（エラーをスローしない）
        }
    }
}
