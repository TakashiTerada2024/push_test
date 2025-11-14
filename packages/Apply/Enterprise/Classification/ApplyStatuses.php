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

namespace Ncc01\Apply\Enterprise\Classification;

use App\Query\RetrieveApplyStatusById;
use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\KeyValueList;

/**
 * ApplyStatuses
 *
 * @package Ncc01\Apply\Enterprise\Classification
 */
class ApplyStatuses extends KeyValueList
{
    /** @var int PRIOR_CONSULTATION データ提供可否 相談中 */
    public const PRIOR_CONSULTATION = 1;
    /** @var int CREATING_DOCUMENT 申出文書 作成中 */
    public const CREATING_DOCUMENT = 2;
    /** @var int CHECKING_DOCUMENT 申出文書 確認中 */
    public const CHECKING_DOCUMENT = 3;
    /** @var int SUBMITTING_DOCUMENT 申出文書 提出中 */
    public const SUBMITTING_DOCUMENT = 4;
    /** @var int UNDER_REVIEW 審査中 */
    public const UNDER_REVIEW = 5;
    /** @var int CANCEL 中止 */
    public const CANCEL = 99;
    /** @var int ACCEPTED 応諾 */
    public const ACCEPTED = 20;

    /**
     * getDefiner
     *
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                self::PRIOR_CONSULTATION => 'データ提供可否 相談中',
                self::CREATING_DOCUMENT => '申出文書 作成中',
                self::CHECKING_DOCUMENT => '申出文書 確認中',
                self::SUBMITTING_DOCUMENT => '申出文書 提出中',
                self::UNDER_REVIEW => '審査中',
                self::CANCEL => '申出中止',
                self::ACCEPTED => '応諾',
            ]
        );
    }

    /**
     * whetherToShowApplyDetail
     *
     * @param int $status
     * @param int|null $applyTypeId
     * @return bool
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @TODO リファクタリング
     */
    public function whetherToShowApplyDetail(int $status, ?int $applyTypeId): bool
    {
        //ステータスNULLの場合、表示不可
        if (is_null($applyTypeId)) {
            return false;
        }
        //事前相談中の場合、表示不可
        if ($status === self::PRIOR_CONSULTATION) {
            return false;
        }
        return true;
    }

    /**
     * getApplyStatusById
     * @param int $id
     * @return int
     * @author anhpd
     */
    public function getApplyStatusById(int $id): int
    {
        $retrieveApplyStatusById = new RetrieveApplyStatusById();
        return $retrieveApplyStatusById->__invoke($id);
    }
}
