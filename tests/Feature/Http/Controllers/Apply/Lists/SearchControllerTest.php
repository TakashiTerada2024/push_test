<?php

namespace Tests\Feature\Http\Controllers\Apply\Lists;

use App\Models\Apply;
use App\Models\ApplyMemo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * 申出検索機能のテストクラス
 *
 * 重要なポイント：
 * 1. 検索対象は以下の3項目で、全て部分一致検索
 *   - メモ (apply_memos.memo)
 *   - 申請者の氏名 (users.name)
 *   - 事務担当者の氏名 (applies.10_clerk_name)
 *
 * 2. アクセス制御
 *   - スーパー管理者：アクセス可能
 *   - 事務局：アクセス可能
 *   - 申請者：アクセス不可（403エラー）
 *
 * 3. 検索結果の表示
 *   - キーワードなしの場合：最新10件を表示
 *   - キーワードありの場合：該当する全件を表示
 */
class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $secretariat;
    private User $applicant;
    private array $applies;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーの作成
        $this->superAdmin = User::factory()->create(['role_id' => 1]); // スーパー管理者
        $this->secretariat = User::factory()->create(['role_id' => 2]); // 事務局
        $this->applicant = User::factory()->create([
            'role_id' => 3,
            'name' => '山田太郎' // 申請者の氏名で検索するためのテストデータ
        ]); // 申請者

        // テスト用の申請データを作成（デフォルトで15件）
        $this->applies = [];
        for ($i = 0; $i < 15; $i++) {
            $apply = Apply::factory()->create([
                'user_id' => $this->applicant->id,
                'subject' => "テスト申請 {$i}",
                'status' => 1, // 作成中
            ]);

            // 各申請にメモを追加
            ApplyMemo::factory()->create([
                'apply_id' => $apply->id,
                'memo' => "テストメモ {$i}",
            ]);

            $this->applies[] = $apply;
        }
    }

    /**
     * スーパー管理者は検索画面にアクセスできることを確認
     */
    public function testスーパー管理者は検索画面にアクセスできる()
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('apply.lists.search'));

        $response->assertStatus(200)
            ->assertViewIs('contents.apply.lists.search');
    }

    /**
     * 事務局は検索画面にアクセスできることを確認
     */
    public function test事務局は検索画面にアクセスできる()
    {
        $response = $this->actingAs($this->secretariat)
            ->get(route('apply.lists.search'));

        $response->assertStatus(200)
            ->assertViewIs('contents.apply.lists.search');
    }

    /**
     * 申請者は検索画面にアクセスできないことを確認（403エラー）
     */
    public function test申請者は検索画面にアクセスできない()
    {
        $response = $this->actingAs($this->applicant)
            ->get(route('apply.lists.search'));

        $response->assertStatus(403);
    }

    /**
     * キーワードなしの場合、最新10件が表示されることを確認
     */
    public function testキーワードなしで最新10件の申出が表示される()
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('apply.lists.search'));

        $response->assertStatus(200)
            ->assertViewHas('applies', function ($applies) {
                return $applies->count() === 10;
            });
    }

    /**
     * メモの内容による部分一致検索ができることを確認
     * 検索対象: apply_memos.memo
     */
    public function testメモの内容で検索できる()
    {
        // 特定の申請を作成
        $apply = Apply::factory()->create([
            'user_id' => $this->applicant->id,
            'subject' => 'テスト申請',
            'status' => 1,
        ]);

        // 特徴的なメモを追加（部分一致で検索可能）
        ApplyMemo::factory()->create([
            'apply_id' => $apply->id,
            'memo' => '特別なテストメモ',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('apply.lists.search', ['keyword' => '特別なテスト']));

        $response->assertStatus(200)
            ->assertViewHas('applies', function ($applies) {
                // 検索結果の内容をダンプ
                Log::info('Search results:', ['applies' => $applies->toArray()]);
                return $applies->contains(function ($apply) {
                    return str_contains($apply->memo, '特別なテスト');
                });
            });
    }

    /**
     * 申請者の氏名による部分一致検索ができることを確認
     * 検索対象: users.name
     */
    public function test申請者の氏名で検索できる()
    {
        // 申請を作成（申請者は setUp で作成した '山田太郎'）
        $apply = Apply::factory()->create([
            'user_id' => $this->applicant->id,
            'subject' => 'テスト申請',
            'status' => 1,
        ]);

        ApplyMemo::factory()->create([
            'apply_id' => $apply->id,
            'memo' => 'テストメモ',
        ]);

        // '山田'で部分一致検索
        $response = $this->actingAs($this->superAdmin)
            ->get(route('apply.lists.search', ['keyword' => '山田']));

        $response->assertStatus(200)
            ->assertViewHas('applies', function ($applies) {
                Log::info('Search results:', ['applies' => $applies->toArray()]);
                return $applies->isNotEmpty();
            });
    }

    /**
     * 事務担当者の氏名による部分一致検索ができることを確認
     * 検索対象: applies.10_clerk_name
     */
    public function test事務担当者の氏名で検索できる()
    {
        // 事務担当者名を指定して申請を作成
        $apply = Apply::factory()->create([
            'user_id' => $this->applicant->id,
            'subject' => 'テスト申請',
            'status' => 1,
            '10_clerk_name' => '鈴木一郎', // 事務担当者名を設定
        ]);

        ApplyMemo::factory()->create([
            'apply_id' => $apply->id,
            'memo' => 'テストメモ',
        ]);

        // '鈴木'で部分一致検索
        $response = $this->actingAs($this->superAdmin)
            ->get(route('apply.lists.search', ['keyword' => '鈴木']));

        $response->assertStatus(200)
            ->assertViewHas('applies', function ($applies) {
                Log::info('Search results:', ['applies' => $applies->toArray()]);
                return $applies->contains(function ($apply) {
                    return str_contains($apply->{'10_clerk_name'}, '鈴木');
                });
            });
    }
}
