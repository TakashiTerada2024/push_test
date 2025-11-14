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

namespace Ncc01\Apply\Application\Service;

use Carbon\Carbon;
use Ncc01\Apply\Enterprise\Entity\ApplicationSkipUrl;
use Ncc01\Apply\Enterprise\Gateway\ApplicationSkipUrlRepositoryInterface;
use Ncc01\Common\Application\GatewayInterface\UuidCreatorInterface;
use InvalidArgumentException;

/**
 * アプリケーションスキップURL ドメインサービス
 * 申出の事前相談をスキップするためのURLを生成・管理するサービス
 */
class ApplicationSkipUrlService
{
    /**
     * コンストラクタ
     *
     * @param ApplicationSkipUrlRepositoryInterface $repository
     * @param UuidCreatorInterface $uuidCreator
     */
    public function __construct(
        private ApplicationSkipUrlRepositoryInterface $repository,
        private UuidCreatorInterface $uuidCreator
    ) {
    }

    /**
     * スキップURLを生成する
     *
     * @param int $applyTypeId 申出種別ID
     * @param int $createdBy 作成者ID（事務局ユーザー）
     * @param int|null $expiresInDays 有効期限（日数）
     * @return ApplicationSkipUrl
     */
    public function generate(int $applyTypeId, int $createdBy, ?int $expiresInDays = 14): ApplicationSkipUrl
    {
        return $this->repository->create($applyTypeId, $createdBy, $expiresInDays);
    }

    /**
     * ULIDからスキップURLを検証する
     * 有効でない場合は例外をスロー
     *
     * @param string $ulid
     * @return ApplicationSkipUrl
     * @throws \InvalidArgumentException スキップURLが無効な場合
     */
    public function validateByUlid(string $ulid): ApplicationSkipUrl
    {
        $skipUrl = $this->repository->findByUlid($ulid);

        if (!$skipUrl) {
            throw new InvalidArgumentException('スキップURLが見つかりません');
        }

        if ($skipUrl->isUsed()) {
            throw new InvalidArgumentException('このスキップURLは既に使用されています');
        }

        if (!$skipUrl->getExpiredAt() || Carbon::now()->isAfter($skipUrl->getExpiredAt())) {
            throw new InvalidArgumentException('このスキップURLは有効期限切れです');
        }

        return $skipUrl;
    }

    /**
     * 有効なスキップURLを取得する
     * 無効な場合はnullを返す（例外をスローしない）
     *
     * @param string $ulid
     * @return ApplicationSkipUrl|null
     */
    public function findValidByUlid(string $ulid): ?ApplicationSkipUrl
    {
        return $this->repository->findValidByUlid($ulid);
    }

    /**
     * スキップURLを使用済みにする
     *
     * @param string $ulid
     * @return ApplicationSkipUrl
     * @throws \InvalidArgumentException スキップURLが無効な場合
     */
    public function markAsUsed(string $ulid): ApplicationSkipUrl
    {
        $skipUrl = $this->validateByUlid($ulid);
        return $this->repository->markAsUsed($skipUrl);
    }
}
