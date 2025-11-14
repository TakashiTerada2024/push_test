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

use GitBalocco\KeyValueList\Classification;
use GitBalocco\KeyValueList\InstantKeyValueList;
use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Contracts\KeyValueListable;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;

/**
 * Attachments
 *
 * @package Ncc01\Apply\Enterprise\Classifictaion
 */
class AttachmentTypes extends Classification
{
    public const WRITTEN_CONSENT = 101;// 同意書等
    public const FORMAT_3_2 = 102;// 様式例第3-2号等
    public const EXPERIENCE = 103;// 実績を示す論文、報告書等
    public const FORMAT_3_1 = 201;// 様式例第3-1号
    public const FORMAT_4_1 = 202;// 様式例第4-1号
    public const OUTSOURCING_CONTRACT_A = 203;// 委託契約書
    public const RESEARCH_PLAN = 204;// 研究計画書等
    public const ETHICAL_REVIEW = 205;// 倫理審査答申書類
    public const FORMAT_2_3 = 301;// 様式例第2-3号及び誓約書
    public const FORMAT_4_2 = 302;// 様式例第4-2号
    public const OUTSOURCING_CONTRACT_B = 303;// 委託契約書
    public const FORMAT_2_1 = 501;// 様式例2-1 別紙
    public const TOTALIZATION_TABLE = 502;// 集計表の様式案等
    public const SAFETY_MANAGEMENT = 701;// 安全管理措置
    public const SECRETARIAT_DOCUMENT = 1001;// 事務局送付資料
    public const REQUEST_FOR_CHANGE_CHECKLIST = 1002;// 変更申出チェックリスト

    /**
     * getDefiner
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefiner(): Definer
    {
        /**
         * optional1 => apply_type=1 行政関係者・リンケージ利用 のバリデーション設定
         * optional2 => apply_type=2 行政関係者・集計統計利用  のバリデーション設定
         * optional3 => apply_type=3 研究者等・リンケージ利用  のバリデーション設定
         * optional4 => apply_type=4 研究者等・集計統計利用  のバリデーション設定
         * can_add_by_applicant => 申出者のアップロード、変更  のバリデーション設定
         */
        return new ArrayDefiner(
            [
                [
                    'id' => self::WRITTEN_CONSENT,
                    //申出様式PDF出力では、「当該研究に係る同意取得説明文書」と記載されている。
                    'name' => '同意書等',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => false,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_3_2,
                    'name' => '様式例第3-2号等',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::EXPERIENCE,
                    'name' => '実績を示す論文、報告書等',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => false,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_3_1,
                    'name' => '様式例第3-1号',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_4_1,
                    //申出様式PDF出力では、「委託の場合は委託契約書等又は様式例第4-1号」として、202、203が併記される形
                    'name' => '様式例第4-1号',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::OUTSOURCING_CONTRACT_A,
                    //申出様式PDF出力では、「委託の場合は委託契約書等又は様式例第4-1号」として、202、203が併記される形
                    'name' => '委託契約書',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::RESEARCH_PLAN,
                    'name' => '研究計画書等',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                //倫理審査答申書類は「承認済み」が選択されている場合に必須。
                [
                    'id' => self::ETHICAL_REVIEW,
                    'name' => '倫理審査答申書類',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_2_3,
                    'name' => '様式例第2-3号及び誓約書',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_4_2,
                    'name' => '様式例第4-2号',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::OUTSOURCING_CONTRACT_B,
                    'name' => '一部委託契約書',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::FORMAT_2_1,
                    'name' => '様式例2-1 別紙',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::TOTALIZATION_TABLE,
                    'name' => '集計表の様式案等',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::SAFETY_MANAGEMENT,
                    'name' => '安全管理措置',
                    'optional1' => false,
                    'optional2' => false,
                    'optional3' => false,
                    'optional4' => false,
                    'can_add_by_applicant' => true
                ],
                [
                    'id' => self::SECRETARIAT_DOCUMENT,
                    'name' => '事務局送付資料',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => false
                ],
                [
                    'id' => self::REQUEST_FOR_CHANGE_CHECKLIST,
                    'name' => '変更申出チェックリスト',
                    'optional1' => true,
                    'optional2' => true,
                    'optional3' => true,
                    'optional4' => true,
                    'can_add_by_applicant' => true
                ]
            ]
        );
    }

    /**
     * getApplicantAttachmentList
     * 申出者がアップロードするattachment_type_idを取得
     *
     * @return array
     */
    public function getApplicantAttachmentList(): array
    {
        //懸念点：listOfNameForApplicant と同じ目的のメソッドなのではないか？
        return [
            self::WRITTEN_CONSENT,
            self::FORMAT_3_2,
            self::EXPERIENCE,
            self::FORMAT_3_1,
            self::FORMAT_4_1,
            self::OUTSOURCING_CONTRACT_A,
            self::RESEARCH_PLAN,
            self::ETHICAL_REVIEW,
            self::FORMAT_2_3,
            self::FORMAT_4_2,
            self::OUTSOURCING_CONTRACT_B,
            self::FORMAT_2_1,
            self::TOTALIZATION_TABLE,
            self::SAFETY_MANAGEMENT,
            self::REQUEST_FOR_CHANGE_CHECKLIST,
        ];
    }

    /**
     * getSecretariatAttachmentList
     * 事務局がアップロードするattachment_type_idを取得
     *
     * @return array
     */
    public function getSecretariatAttachmentList(): array
    {
        return [
            self::SECRETARIAT_DOCUMENT
        ];
    }

    /**
     * listOfId
     *
     * @return KeyValueListable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function listOfId(): KeyValueListable
    {
        return $this->listOf($this->getIdentityIndex());
    }

    /**
     * listOfName
     *
     * @return KeyValueListable
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function listOfName(): KeyValueListable
    {
        return parent::listOf('name');
    }

    /**
     * listOfNameForApplicant
     *
     * @return KeyValueListable
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function listOfNameForApplicant(): KeyValueListable
    {
        $array = [];
        foreach ($this->getData() as $row) {
            $result = array_filter($row, function ($val, $key) {
                return $key == 'can_add_by_applicant' && $val == true;
            }, ARRAY_FILTER_USE_BOTH);

            if (!empty($result)) {
                $array[] = $row;
            }
        }

        $definer = new ArrayDefiner(array_column($array, 'name', $this->getIdentityIndex()));
        return new InstantKeyValueList($definer);
    }

    /**
     * fetchIdByValue
     * 指定した値(name)を持つidを返却
     *
     * @param string $value
     * @return int
     */
    public function findIdByName(string $value): int
    {
        $num = array_search($value, array_column($this->getData(), 'name'));
        return $this->getData()[$num]['id'];
    }

    public function nameOf(int $id): string
    {
        return $this->valueOf('name', $id) ?? "";
    }

    public function isMandatory(int $id, ?int $applyType): bool
    {
        $result = $this->valueOf($this->optionalKeyOfApplyType($applyType), $id);
        if (is_bool($result)) {
            return !$result;
        }
        return false;
    }

    protected function getIdentityIndex()
    {
        return 'id';
    }

    private function optionalKeyOfApplyType(?int $applyType): string
    {
        return 'optional' . ((string)$applyType);
    }
}
