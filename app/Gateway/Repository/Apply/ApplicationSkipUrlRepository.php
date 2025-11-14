<?php

namespace App\Gateway\Repository\Apply;

use App\Models\ApplicationSkipUrl as ApplicationSkipUrlModel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Ncc01\Apply\Enterprise\Entity\ApplicationSkipUrl;
use Ncc01\Apply\Enterprise\Gateway\ApplicationSkipUrlRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
 */
class ApplicationSkipUrlRepository implements ApplicationSkipUrlRepositoryInterface
{
    /**
     * スキップURLエンティティを保存する
     *
     * @param ApplicationSkipUrl $applicationSkipUrl
     * @return ApplicationSkipUrl
     */
    public function save(ApplicationSkipUrl $applicationSkipUrl): ApplicationSkipUrl
    {
        $model = ApplicationSkipUrlModel::fromEntity($applicationSkipUrl);
        $model->save();

        return $model->toEntity();
    }

    /**
     * IDからスキップURLエンティティを取得する
     *
     * @param int $id
     * @return ApplicationSkipUrl|null
     */
    public function findById(int $id): ?ApplicationSkipUrl
    {
        $model = ApplicationSkipUrlModel::find($id);

        if (!$model) {
            return null;
        }

        return $model->toEntity();
    }

    /**
     * ULIDからスキップURLエンティティを取得する
     *
     * @param string $ulid
     * @return ApplicationSkipUrl|null
     */
    public function findByUlid(string $ulid): ?ApplicationSkipUrl
    {
        $model = ApplicationSkipUrlModel::where('ulid', $ulid)->first();

        if (!$model) {
            return null;
        }

        return $model->toEntity();
    }

    /**
     * スキップURLエンティティを使用済みにする
     *
     * @param ApplicationSkipUrl $applicationSkipUrl
     * @return ApplicationSkipUrl
     */
    public function markAsUsed(ApplicationSkipUrl $applicationSkipUrl): ApplicationSkipUrl
    {
        $applicationSkipUrl->markAsUsed();
        return $this->save($applicationSkipUrl);
    }

    /**
     * @inheritDoc
     */
    public function create(int $applyTypeId, int $userId, ?int $expiresInDays = 14): ApplicationSkipUrl
    {
        // UUIDベースのULIDを生成
        $ulid = $this->generateUlid();
        $expiredAt = Carbon::now()->addDays($expiresInDays);

        $entity = ApplicationSkipUrl::create(
            $ulid,
            $applyTypeId,
            $userId,
            $expiredAt
        );

        return $this->save($entity);
    }

    /**
     * @inheritDoc
     */
    public function findValidByUlid(string $ulid): ?ApplicationSkipUrl
    {
        $model = ApplicationSkipUrlModel::where('ulid', $ulid)
            ->where('is_used', false)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$model) {
            return null;
        }

        return $model->toEntity();
    }

    /**
     * ULIDを生成する
     * UUIDベースでタイムスタンプを付加して、ソート可能な一意な識別子を生成
     *
     * @return string
     */
    private function generateUlid(): string
    {
        // タイムスタンプ（ミリ秒）をBase32の文字列に変換（先頭10文字）
        $timestamp = $this->encodeTimestamp(Carbon::now()->getPreciseTimestamp(3));

        // UUIDからランダム部分を生成（残り16文字）
        $uuid = str_replace('-', '', Uuid::uuid4()->toString());
        $random = substr($this->base32Encode($uuid), 0, 16);

        return $timestamp . $random;
    }

    /**
     * タイムスタンプをBase32エンコードする
     *
     * @param int $timestamp
     * @return string
     */
    private function encodeTimestamp(int $timestamp): string
    {
        $encodedTime = '';
        $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ'; // Base32文字セット（I, L, Oを除外）

        // 48ビットのタイムスタンプをエンコード（10文字のBase32文字列に）
        for ($i = 9; $i >= 0; $i--) {
            $encodedTime = $chars[$timestamp % 32] . $encodedTime;
            $timestamp = (int)($timestamp / 32);
        }

        return $encodedTime;
    }

    /**
     * 文字列をBase32エンコードする（簡易実装）
     *
     * @param string $input
     * @return string
     */
    private function base32Encode(string $input): string
    {
        $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ'; // Base32文字セット（I, L, Oを除外）
        $result = '';

        // 入力文字列をビットに変換し、5ビットずつBase32文字に変換
        $bits = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $bits .= str_pad(decbin(ord($input[$i])), 8, '0', STR_PAD_LEFT);
        }

        // 5ビットずつ処理
        for ($i = 0; $i < strlen($bits); $i += 5) {
            $chunk = substr($bits, $i, 5);
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            }
            $result .= $chars[bindec($chunk)];
        }

        return $result;
    }
}
