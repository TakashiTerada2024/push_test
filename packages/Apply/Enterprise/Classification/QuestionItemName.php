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

use GitBalocco\KeyValueList\Contracts\Definer;
use GitBalocco\KeyValueList\Definer\ArrayDefiner;
use GitBalocco\KeyValueList\KeyValueList;

/**
 * QuestionItemName
 * 質問項目名称表
 * @package Ncc01\Apply\Enterprise\Classification
 */
class QuestionItemName extends KeyValueList
{
    /**
     * getDefiner
     *
     * @return Definer
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefiner(): Definer
    {
        return new ArrayDefiner(
            [
                'affiliation_1' => '所属',
                'affiliation_2' => '法人(その他の団体) の名称',
                'department' => '部署',
                'status' => '申出ステータス',
                '2_purpose_of_use' => '利用目的',
                '2_need_to_use' => '利用の必要性',
                '2_ethical_review_status' => '倫理審査進捗状況',
                '2_ethical_review_remark' => '倫理審査進捗状況 備考',
                '2_ethical_review_board' => '倫理審査委員会',
                '2_ethical_review_board_name' => '倫理審査委員会 名称',
                '2_ethical_review_board_code' => '倫理審査委員会 承認番号',
                '2_ethical_review_board_date' => '倫理審査委員会 承認年月日',
                '4_year_of_diagnose' => '診断年次',
                '4_area_type' => '地域',
                '4_area_prefectures' => '都道府県',
                '4_idc_type' => '疾病分類',
                '4_idc_detail' => '疾病分類詳細',
                '4_is_alive_required' => '生存しているか死亡しているかの別',
                '4_is_alive_date_required' => '生存を確認した直近の日又は死亡日',
                '4_is_cause_of_death_required' => '死亡の原因',
                '4_sex' => '性別',
                '4_range_of_age_type' => '年齢',
                '4_range_of_age_detail' => '年齢(詳細)',
                '5_research_method' => '調査研究方法',
                '6_usage_period' => '利用期間',
                '6_research_period' => '研究期間',
                '8_scheduled_to_be_announced' => '公表予定時期',
                '9_treatment_after_use' => '利用後の処置',
                '10_clerk_name' => '事務担当者 氏名',
                '10_clerk_contact_address' => '事務担当者 住所',
                '10_clerk_contact_email' => '事務担当者 メールアドレス',
                '10_clerk_contact_phone_number' => '事務担当者 電話番号',
                '10_clerk_contact_extension_phone_number' => '事務担当者 内線',
                '10_applicant_type' => '申出者 種別',
                '10_applicant_name_1' => '申出者 氏名',
                '10_applicant_name_2' => '法人(その他の団体) の代表者氏名',
                '10_applicant_address_1' => '申出者 住所',
                '10_applicant_address_2' => '法人(その他の団体) の所在地',
                '10_applicant_birthday' => '申出者 生年月日',
                '10_remark' => 'その他必要事項',
                '10_applicant_name_kana' => '申出者 フリガナ',
                '10_applicant_phone_number' => '申出者 内線番号'
            ]
        );
    }
}
