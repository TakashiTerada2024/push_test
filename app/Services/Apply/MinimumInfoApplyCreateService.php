<?php

namespace App\Services\Apply;

use App\Models\Apply;
use App\Models\User;
use App\Notifications\DatabaseNotificationOfApply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ncc01\Apply\Enterprise\Classification\ApplyStatuses;
use InvalidArgumentException;
use Ncc01\Messaging\Application\GatewayInterface\MessageSenderInterface;
use Ncc01\User\Enterprise\Role;

/**
 * 最低限必要な情報から申出を作成するサービス
 */
class MinimumInfoApplyCreateService
{
    /**
     * @var SkipPreliminaryService
     */
    private $skipPreliminaryService;

    /**
     * @var MessageSenderInterface
     */
    private $messageSender;

    /**
     * コンストラクタ
     *
     * @param SkipPreliminaryService $skipPreliminaryService
     * @param MessageSenderInterface $messageSender
     */
    public function __construct(
        SkipPreliminaryService $skipPreliminaryService,
        MessageSenderInterface $messageSender
    ) {
        $this->skipPreliminaryService = $skipPreliminaryService;
        $this->messageSender = $messageSender;
    }

    /**
     * 最低限必要な情報から申出を作成する
     *
     * @param array $parameters
     * @param string $skipUrlUlid スキップURL用トークン
     * @return int 作成された申出のID
     * @throws \InvalidArgumentException パラメータが不正な場合
     * @throws \Exception 処理中にエラーが発生した場合
     */
    public function createApplyFromMinimumInfo(array $parameters, string $skipUrlUlid): int
    {
        // 必須パラメータの検証
        $this->validateParameters($parameters);

        try {
            DB::beginTransaction();

            // まずスキップURLトークンを検証
            $tokenData = $this->skipPreliminaryService->validateToken($skipUrlUlid);
            if (!$tokenData) {
                throw new InvalidArgumentException('無効なスキップURLです');
            }

            // 申出を作成
            $apply = new Apply();
            $apply->user_id = Auth::id();
            $apply->type_id = $tokenData['apply_type_id']; // トークンから申出種別IDを取得
            $apply->subject = $parameters['subject'];
            $apply->status = ApplyStatuses::CREATING_DOCUMENT; // 申出文書作成中ステータス(2)

            // 連絡先情報を設定
            $apply->{'10_applicant_name'} = $parameters['contact_name'];
            $apply->affiliation = $parameters['contact_affiliation'];

            // その他のパラメータも設定可能

            // 保存
            $apply->save();
            
            // 申出作成メッセージを追加
            $this->addSkipPreliminaryMessage($apply->id);

            // スキップURLを使用済みにマーク
            $marked = $this->skipPreliminaryService->markTokenAsUsed($skipUrlUlid);
            if (!$marked) {
                Log::warning('スキップURLを使用済みにマークできませんでした', [
                    'ulid' => $skipUrlUlid,
                    'apply_id' => $apply->id
                ]);
                // エラーだがロールバックしない（申出は作成成功）
            }

            DB::commit();

            return $apply->id;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('申出作成中にエラーが発生しました: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 事前相談スキップで申出が作成されたことを示すメッセージを追加
     *
     * @param int $applyId 申出ID
     * @return void
     */
    private function addSkipPreliminaryMessage(int $applyId): void
    {
        try {
            // 事務局ユーザーを1名取得 (role_id = 2 が事務局)
            $secretariat = User::where('role_id', Role::SECRETARIAT_ROLE_ID)->first();
            
            if (!$secretariat) {
                Log::warning('事務局ユーザーが見つかりません。メッセージ登録をスキップします。', [
                    'apply_id' => $applyId
                ]);
                return;
            }
            
            // メッセージペイロードを作成
            $payload = [
                'body' => '事前相談スキップで申出が作成されました',
                'fromId' => Auth::id(),
                'fromName' => Auth::user()->name,
            ];
            
            // 事務局ユーザーにメッセージを送信
            $this->messageSender->send($payload, $applyId, $secretariat->id);
            
            Log::info('事前相談スキップメッセージを登録しました', [
                'apply_id' => $applyId,
                'secretariat_id' => $secretariat->id
            ]);
        } catch (\Exception $e) {
            // メッセージ登録エラーはログに記録するが、申出作成処理は続行する
            Log::error('事前相談スキップメッセージの登録に失敗しました: ' . $e->getMessage(), [
                'apply_id' => $applyId
            ]);
        }
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
    }
}
