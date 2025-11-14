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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Query;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Builder;
use Ncc01\Attachment\Application\QueryServiceInterface\RetrieveAttachmentsOfApplyAndTypeQueryInterface;

/**
 * RetrieveAttachmentsOfApplyAndTypeQuery
 * 申出に関する添付資料一覧画面専用のQuery
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @package App\Query
 */
class RetrieveAttachmentsOfApplyAndTypeQuery implements RetrieveAttachmentsOfApplyAndTypeQueryInterface
{
    /**
     * __invoke
     *
     * @param int $applyId
     * @param bool|null $isGroup
     * @param array $typeList 取得する種別
     * @param array $statusList
     * @param bool $includeTypeNull 種別nullを含めるかどうか
     * @return array
     * @author m.shomura <m.shomura@balocco.info>
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag) 2025-03-05 yamamoto
     */
    public function __invoke(
        int $applyId,
        ?bool $isGroup = null,
        array $typeList = [],
        array $statusList = [],
        bool $includeTypeNull = false
    ): array {
        /** @var Builder $queryBuilder */
        $queryBuilder = Attachment::where('apply_id', $applyId);

        $this->buildWhereClauseForType($queryBuilder, $typeList, $includeTypeNull);
        if (!empty($statusList)) {
            $queryBuilder->whereIn('status', $statusList);
        }
        $queryBuilder->orderBy('attachment_type_id', 'ASC')
            ->orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC');

        /** @var \Illuminate\Database\Eloquent\Collection $attachments */
        $attachments = $queryBuilder->get([
            'id',
            'name',
            'user_id',
            'attachment_type_id',
            'path',
            'created_at',
            'status'
        ]);

        if (!$isGroup) {
            return $attachments->toArray();
        }

        // 添付ファイルの種類ごとに表示するので、attachment_type_id ごとに分割した上で配列として返却する。
        $grouped = $attachments->groupBy('attachment_type_id');
        return $grouped->toArray();
    }

    /**
     * buildWhereClauseForType
     * attachment_type_idに関するwhere句作成
     *
     * @param Builder $queryBuilder
     * @param array $typeList
     * @param bool $includeTypeNull
     * @return void
     */
    private function buildWhereClauseForType(Builder $queryBuilder, array $typeList, bool $includeTypeNull): void
    {
        if (!empty($typeList) && $includeTypeNull) {
            $queryBuilder->where(function ($query) use ($typeList) {
                $query
                    ->whereIn('attachment_type_id', $typeList)
                    ->orWhereNull('attachment_type_id');
            });
            return;
        }
        $queryBuilder->whereIn('attachment_type_id', $typeList);
    }
}
