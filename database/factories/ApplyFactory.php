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

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplyFactory extends Factory
{
    public function definition()
    {
        return [
            'type_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'subject' => $this->faker->text(50),
            'affiliation' => $this->faker->text(50) ,
            'department' => $this->faker->text(100),
            'status' => 1,
            "2_purpose_of_use" => $this->faker->text(100),
            "2_need_to_use" => $this->faker->text(100),
            "2_ethical_review_status" => 0,
            "2_ethical_review_remark" => $this->faker->text(100),
            "2_ethical_review_board_name" => $this->faker->text(100),
            "2_ethical_review_board_code" => $this->faker->text(100),
            "2_ethical_review_board_date" => now()->format('Y-m-d'),
            "4_idc_detail" => $this->faker->text(100),
        ];
    }

    /**
     * 提出中状態の申請と通知情報を作成する
     * 事務局側の画面に表示されるデータセット。
     */
    public function submitting()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 4, // SUBMITTING_DOCUMENT
                'submitted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->afterCreating(function ($apply) {
            // 通知を作成
            $notification = \Illuminate\Notifications\DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplySubmitted',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->faker->randomElement([1, 2]), // 事務局ユーザーID
                'data' => json_encode([
                    'apply_id' => $apply->id,
                    'message' => '申請が提出されました',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'read_at' => $this->faker->randomElement([now(), null]),
            ]);

            // latest_notificationsテーブルに登録
            \Illuminate\Support\Facades\DB::table('latest_notifications')->insert([
                'apply_id' => $apply->id,
                'notification_id' => $notification->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * 作成中状態の申請を作成
     */
    public function creating()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 2, // 作成中
                'type_id' => $this->faker->randomElement([1, 2, 3, 4]),
                'subject' => 'テスト申請（作成中）',
                'affiliation' => 'テスト研究所',
                '10_applicant_name' => 'テストユーザー',
                '10_applicant_name_kana' => 'テストユーザー',
                '10_applicant_phone_number' => '03-9876-5432',
                '6_usage_period_end' => now()->addYear()->format('Y-m-d'),
                'summary' => 'テスト用の申請データです',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->afterCreating(function ($apply) {
            // 通知を作成
            $notification = \Illuminate\Notifications\DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplyCreated',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->faker->randomElement([1, 2]), // 事務局ユーザーID
                'data' => json_encode([
                    'apply_id' => $apply->id,
                    'message' => '申請が作成されました',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'read_at' => $this->faker->randomElement([now(), null]),
            ]);

            // latest_notificationsテーブルに登録
            \Illuminate\Support\Facades\DB::table('latest_notifications')->insert([
                'apply_id' => $apply->id,
                'notification_id' => $notification->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * 承認済み状態の申請を作成
     */
    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 20, // 承認済み
                'type_id' => $this->faker->randomElement([1, 2, 3, 4]),
                'subject' => 'テスト申請（承認済み）',
                'affiliation' => 'テスト病院',
                '10_applicant_name' => 'テストユーザー',
                '10_applicant_name_kana' => 'テストユーザー',
                '10_applicant_phone_number' => '03-5555-1234',
                '6_usage_period_end' => now()->addYear()->format('Y-m-d'),
                'summary' => 'テスト用の申請データです',
                'submitted_at' => now()->subDays(5),
                'accepted_at' => now()->subDays(2),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ];
        })->afterCreating(function ($apply) {
            // 通知を作成
            $notification = \Illuminate\Notifications\DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplyAccepted',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->faker->randomElement([1, 2]), // 事務局ユーザーID
                'data' => json_encode([
                    'apply_id' => $apply->id,
                    'message' => '申請が承認されました',
                ]),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
                'read_at' => now()->subDay(),
            ]);

            // latest_notificationsテーブルに登録
            \Illuminate\Support\Facades\DB::table('latest_notifications')->insert([
                'apply_id' => $apply->id,
                'notification_id' => $notification->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]);
        });
    }

    /**
     * 確認中状態の申請を作成
     */
    public function checking()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 3, // CHECKING_DOCUMENT
                'type_id' => $this->faker->randomElement([1, 2, 3, 4]),
                'subject' => 'テスト申請（確認中）',
                'affiliation' => 'テスト病院',
                '10_applicant_name' => 'テストユーザー',
                '10_applicant_name_kana' => 'テストユーザー',
                '10_applicant_phone_number' => '03-5555-1234',
                '6_usage_period_end' => now()->addYear()->format('Y-m-d'),
                'summary' => 'テスト用の申請データです',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ];
        })->afterCreating(function ($apply) {
            // 通知を作成
            $notification = \Illuminate\Notifications\DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplyChecking',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->faker->randomElement([1, 2]), // 事務局ユーザーID
                'data' => json_encode([
                    'apply_id' => $apply->id,
                    'message' => '申請が確認中になりました',
                ]),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
                'read_at' => $this->faker->randomElement([now(), null]),
            ]);

            // latest_notificationsテーブルに登録
            \Illuminate\Support\Facades\DB::table('latest_notifications')->insert([
                'apply_id' => $apply->id,
                'notification_id' => $notification->id,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]);
        });
    }

    /**
     * 申出文書承認依頼が可能な状態のデータを作成
     * 文書作成中ステータスで、全ての必須項目が入力済みの状態
     *
     * @param int|null $userId 申請者のユーザーID
     * @return $this
     */
    public function readyForChecking(?int $userId = null)
    {
        if (is_null($userId)) {
            $userId = \App\Models\User::factory()->create()->id;
        }

        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
                'status' => 2, // CREATING_DOCUMENT
                'type_id' => $this->faker->randomElement([1, 2, 3, 4]),
                'subject' => 'テスト申請（承認依頼準備完了）',
                'affiliation' => 'テスト研究所',
                'department' => 'テスト部門',
                '2_purpose_of_use' => 'テスト目的',
                '2_need_to_use' => 'テスト必要性',
                '2_ethical_review_status' => 1, // 承認済み
                '2_ethical_review_board_name' => 'テスト倫理審査委員会',
                '2_ethical_review_board_code' => 'TEST-001',
                '2_ethical_review_board_date' => now()->format('Y-m-d'),
                '10_applicant_type' => 1, // 申出者種別（1: 国、2: 都道府県、3: 市町村等）
                '4_year_of_diagnose_start' => 2016,
                '4_year_of_diagnose_end' => now()->month < 10 ? now()->year - 3 : now()->year - 2, // 現在の月に応じて終了年を設定
                '4_area_prefectures' => json_encode([1, 2, 3]), // 複数の都道府県
                '4_idc_type' => 1,
                '4_idc_detail' => 'C00-C97',
                '4_is_alive_required' => 1,
                '4_is_alive_date_required' => 1,
                '4_is_cause_of_death_required' => 1,
                '4_sex' => 1,
                '4_range_of_age_type' => 1,
                '5_research_method' => '一般公開情報[e-statや院内がん登録全国集計等]と全国がん登録情報の地域別の情報を用いて対象都市の年齢調整罹患率を算出し、地域別の特性を算出し、特性と年齢調整罹患率との相関を観察する。更に、がん種別、性別ごとに層別化の検証も行う。',
                '6_usage_period_end' => now()->addYear()->format('Y-m-d'),
                '6_research_period_start' => now()->format('Y-m-d'),
                '6_research_period_end' => now()->addYear()->format('Y-m-d'),
                '8_scheduled_to_be_announced' => 1,
                '9_treatment_after_use' => 1,
                '10_applicant_name' => 'テストユーザー',
                '10_applicant_name_kana' => 'テストユーザー',
                '10_applicant_phone_number' => '03-1234-5678',
                '10_applicant_address' => 'テスト住所',
                '10_applicant_birthday' => '1980-01-01',
                '10_clerk_name' => 'テスト事務担当者',
                '10_clerk_contact_address' => '〒101-0032 東京都千代田区岩本町2-9-9',
                '10_clerk_contact_email' => 'clerk@example.com',
                '10_clerk_contact_phone_number' => '03-9999-8888',
                '10_clerk_contact_extension_phone_number' => '1234', // 内線番号は任意
                'summary' => 'テスト用の申請データです',
                'created_at' => now(),
                'updated_at' => now(),
                '3_number_of_users' => 1, // 利用人数を設定
            ];
        })->afterCreating(function ($apply) use ($userId) {
            // 利用者情報を作成
            \App\Models\ApplyUser::factory()->create([
                'apply_id' => $apply->id,
                'name' => 'テスト利用者',
                'institution' => 'テスト研究機関',
                'position' => '研究員',
                'role' => '研究責任者'
            ]);

            // 必要な添付ファイルを作成
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 301, // 様式例第2-3号及び誓約書
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 101,
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 201, // 様式例第3-1号
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 205, // 倫理審査答申書類
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 501,
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 502,
                'status' => 3, // 承認済み
            ]);
            \App\Models\Attachment::factory()->create([
                'apply_id' => $apply->id,
                'user_id' => $userId,
                'attachment_type_id' => 701,
                'status' => 3, // 承認済み
            ]);

            // 通知を作成
            $notification = \Illuminate\Notifications\DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\ApplyCreated',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->faker->randomElement([1, 2]), // 事務局ユーザーID
                'data' => json_encode([
                    'apply_id' => $apply->id,
                    'message' => '申請が作成されました',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'read_at' => $this->faker->randomElement([now(), null]),
            ]);

            // latest_notificationsテーブルに登録
            \Illuminate\Support\Facades\DB::table('latest_notifications')->insert([
                'apply_id' => $apply->id,
                'notification_id' => $notification->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
