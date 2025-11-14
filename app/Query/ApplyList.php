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

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Ncc01\Apply\Application\QueryInterface\ApplyListInterface;
use Ncc01\Apply\Application\QueryParameterInterface\ApplyListParameterInterface;

/**
 * ApplyList
 *
 * @package App\Query
 */
class ApplyList implements ApplyListInterface
{
    /**
     * __invoke
     *
     * @param ApplyListParameterInterface $parameter
     * @return iterable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __invoke(ApplyListParameterInterface $parameter): iterable
    {
        $builder = DB::table('applies');
        $builder->select(
            [
                'applies.id',
                'applies.affiliation',
                'applies.subject',
                'applies.type_id',
                'applies.10_applicant_name',
                'applies.6_usage_period_end',
                'applies.status',
                //開封日時、nullの場合未読としてアイコンが赤になる
                'notifications.read_at',
                //受信日時
                'notifications.created_at',
                'apply_histories.source_apply_id',
                'apply_histories.apply_id',
                'apply_memos.memo',
            ]
        );
        $builder->leftJoin('latest_notifications', 'applies.id', '=', 'latest_notifications.apply_id');
        $builder->leftJoin('notifications', 'latest_notifications.notification_id', '=', 'notifications.id');
        $builder->leftJoin('apply_memos', 'applies.id', '=', 'apply_memos.apply_id');

        // left join with apply_histories table
        $this->handleIsShowAccepted($builder, $parameter->getIsShowAccepted());

        //パラメタ指定条件をクエリに反映
        $this->handleStatusCondition($builder, $parameter->getStatus());
        $this->handleIsRepliedBySecretariatCondition($builder, $parameter->getIsRepliedBySecretariat());
        $this->handleTypeCondition($builder, $parameter->getType());
        //直近通知の受信日時降順
        $builder->orderBy('notifications.created_at', 'DESC');

        return $builder->cursor();
    }

    /**
     * handleStatusCondition
     *
     * @param $builder
     * @param array|null $statusIds
     * @return mixed
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function handleStatusCondition(Builder $builder, ?array $statusIds)
    {
        if (is_null($statusIds)) {
            return $builder;
        }
        $builder->whereIn('status', $statusIds);
        return $builder;
    }

    /**
     * handleIsRepliedBySecretariatCondition
     *
     * @param Builder $builder
     * @param bool|null $isReplied
     * @return Builder
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function handleIsRepliedBySecretariatCondition(Builder $builder, ?bool $isReplied)
    {
        if (is_null($isReplied)) {
            return $builder;
        }
        if ($isReplied === true) {
            $builder->where('notifications.notifiable_id', '<>', '2');
            return $builder;
        }

        $builder->where('notifications.notifiable_id', '=', '2');
        return $builder;
    }

    /**
     * handleTypeCondition
     *
     * @param Builder $builder
     * @param array|null $types
     * @return Builder
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    private function handleTypeCondition(Builder $builder, ?array $types)
    {
        if (is_null($types)) {
            return $builder;
        }

        $builder->whereIn('applies.type_id', $types);
        return $builder;
    }

    /**
     * handleIsShowAccepted
     * TODO:psalm警告に対する修正（leftJoin の第一引数の型がstringでない）
     * TODO:リファクタリング、引数のフラグを削除するべき
     * @param Builder $builder
     * @param bool|null $isShowAccepted
     * @return Builder
     * @author anhpd
     */
    private function handleIsShowAccepted(Builder $builder, ?bool $isShowAccepted): Builder
    {
        if ($isShowAccepted) {
            return $builder->leftJoin(
                DB::raw('
                    (SELECT source_apply_id, MIN(apply_id) AS apply_id
                    FROM apply_histories
                    GROUP BY source_apply_id) AS apply_histories
                '),
                'apply_histories.source_apply_id',
                '=',
                'applies.id'
            );
        }
        return $builder->leftJoin(
            DB::raw('
                (SELECT apply_id, MAX(source_apply_id) AS source_apply_id
                FROM apply_histories
                GROUP BY apply_id) AS apply_histories
            '),
            'apply_histories.apply_id',
            '=',
            'applies.id'
        );
    }
}
