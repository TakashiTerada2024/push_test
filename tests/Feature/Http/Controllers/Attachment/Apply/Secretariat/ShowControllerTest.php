<?php

namespace Apply\Secretariat;

use App\Models\Apply;
use App\Models\User;
use Tests\Feature\FeatureTestBase;

class ShowControllerTest extends FeatureTestBase
{
    /** @var string 事務局専用の添付資料追加画面 */
    public const Route = '/attachment/apply/secretariat/show/{id}';

    /**
     * test_申出作成者がアクセスした場合200を返却する
     * @covers \App\Http\Controllers\Attachment\Apply\Secretariat\ShowController
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_申出者本人がアクセスした場合200を返却する()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $this->actingAs($user);

        /* Execution */
        $response = $this->get($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(200);
    }

    /**
     * test_申出作成者以外の申出者権限ログイン状態でアクセスした場合403を返却する
     * @covers \App\Http\Controllers\Attachment\Apply\Secretariat\ShowController
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_申出作成者以外の申出者権限ログイン状態でアクセスした場合403を返却する()
    {
        /* Preparations */
        $ownerUser = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $ownerUser])->create();
        $user = User::factory()->state(['role_id' => 3])->create();
        $this->actingAs($user);

        /* Execution */
        $response = $this->get($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(403);
    }

    /**
     * test_未ログイン状態でアクセスした場合302を返却する
     * @covers \App\Http\Controllers\Attachment\Apply\Secretariat\ShowController
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_未ログイン状態でアクセスした場合302を返却する()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 1])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();

        /* Execution */
        $response = $this->get($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(302);
        $response->assertRedirect('/auth/login');
    }

    /**
     * test_事務局権限ログイン状態でアクセスした場合200を返却する
     * @covers \App\Http\Controllers\Attachment\Apply\Secretariat\ShowController
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_事務局権限ログイン状態でアクセスした場合200を返却する()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 2])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $this->actingAs($user);

        /* Execution */
        $response = $this->get($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(200);
    }

    /**
     * route
     * @param int $applyId
     * @return string
     * @covers \App\Http\Controllers\Attachment\Apply\Secretariat\ShowController
     * @author m.shomura <m.shomura@balocco.info>
     */
    private function route($applyId): string
    {
        return str_replace('{id}', $applyId, self::Route);
    }
}
