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

namespace App\Gateway\Repository\ApplyMemo;

use App\Models\Apply;
use App\Models\ApplyMemo;
use Ncc01\Apply\Application\Gateway\ApplyMemoRepositoryInterface;

/**
 * ApplyMemoRepository
 *
 * @package App\Repository\ApplyMemo
 */
class ApplyMemoRepository implements ApplyMemoRepositoryInterface
{
    /**
     * save
     *
     * @param int $applyId
     * @param string $memo
     * @return int
     * @author m.shomura <m.shomura@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Repositoryの実装クラスにおいては、Eloquent直接利用OK
     */
    public function save(int $applyId, string $memo): int
    {
        Apply::findOrFail($applyId);
        $model = ApplyMemo::firstOrNew(['apply_id' => $applyId]);

        $model->apply_id = $applyId;
        $model->memo = $memo;
        $model->save();

        return $model->id;
    }
}
