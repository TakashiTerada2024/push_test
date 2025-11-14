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

namespace App\Query;

use Illuminate\Support\Facades\DB;
use Ncc01\Apply\Application\QueryInterface\ApplySearchInterface;
use Ncc01\Apply\Application\QueryParameterInterface\ApplySearchParameterInterface;

/**
 * ApplySearch
 *
 * @package App\Query
 */
class ApplySearch implements ApplySearchInterface
{
    /**
     * キーワードによる申出検索を実行
     *
     * @param ApplySearchParameterInterface $parameter
     * @return iterable
     */
    public function __invoke(ApplySearchParameterInterface $parameter): iterable
    {
        $keyword = $parameter->getKeyword();

        if (empty($keyword)) {
            // キーワードが空の場合は更新日が新しい順に10件表示
            return $this->getLatestApplies();
        }

        // キーワードによる検索
        return $this->searchByKeyword($keyword);
    }

    /**
     * キーワードによる検索を実行
     *
     * @param string $keyword
     * @return iterable
     * @psalm-suppress ImplicitToStringCast
     */
    private function searchByKeyword(string $keyword): iterable
    {
        $query = DB::table('applies')
            ->select([
                'applies.*',
                'apply_memos.memo',
                'notifications.read_at',
                'notifications.created_at',
                'apply_histories.source_apply_id',
                'apply_histories.apply_id'
            ])
            ->leftJoin('apply_memos', 'applies.id', '=', 'apply_memos.apply_id')
            ->leftJoin('latest_notifications', 'applies.id', '=', 'latest_notifications.apply_id')
            ->leftJoin('notifications', 'latest_notifications.notification_id', '=', 'notifications.id')
            ->leftJoin('users', 'applies.user_id', '=', 'users.id')
            ->leftJoin(DB::raw('(
                SELECT source_apply_id, MIN(apply_id) AS apply_id
                FROM apply_histories
                GROUP BY source_apply_id
            ) AS apply_histories'), function ($join) {
                $join->on('apply_histories.source_apply_id', '=', 'applies.id');
            })
            ->where(function ($query) use ($keyword) {
                $query->where('apply_memos.memo', 'like', '%' . $keyword . '%')
                    ->orWhere('users.name', 'like', '%' . $keyword . '%')
                    ->orWhere('applies.10_clerk_name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('applies.updated_at', 'desc');

        return $query->get();
    }

    /**
     * 最新の申出を取得（キーワードが空の場合）
     *
     * @return iterable
     * @psalm-suppress ImplicitToStringCast
     */
    private function getLatestApplies(): iterable
    {
        $query = DB::table('applies')
            ->select([
                'applies.*',
                'apply_memos.memo',
                'notifications.read_at',
                'notifications.created_at',
                'apply_histories.source_apply_id',
                'apply_histories.apply_id'
            ])
            ->leftJoin('apply_memos', 'applies.id', '=', 'apply_memos.apply_id')
            ->leftJoin('latest_notifications', 'applies.id', '=', 'latest_notifications.apply_id')
            ->leftJoin('notifications', 'latest_notifications.notification_id', '=', 'notifications.id')
            ->leftJoin(DB::raw('(
                SELECT source_apply_id, MIN(apply_id) AS apply_id
                FROM apply_histories
                GROUP BY source_apply_id
            ) apply_histories'), function ($join) {
                $join->on('apply_histories.source_apply_id', '=', 'applies.id');
            })
            ->orderBy('applies.updated_at', 'desc')
            ->limit(10);

        return $query->get();
    }
}
