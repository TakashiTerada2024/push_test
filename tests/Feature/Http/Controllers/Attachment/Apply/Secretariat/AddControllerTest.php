<?php

namespace Apply\Secretariat;

use App\Models\Apply;
use App\Models\User;
use App\Notifications\MessageForAddAttachmentBySecretariat;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Mockery\MockInterface;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use Ncc01\Attachment\Application\Usecase\SaveAttachmentInterface;
use Tests\Feature\FeatureTestBase;

class AddControllerTest extends FeatureTestBase
{
    /** @var string 事務局専用の添付資料追加画面 */
    public const Route = '/attachment/apply/secretariat/add/{id}';

    public function test_申出者権限ログイン状態でアクセスした場合403を返却する()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $this->actingAs($user);

        /* Execution */
        $response = $this->post($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(403);
    }

    public function test_未ログイン状態でのアクセス時にログイン画面へリダイレクトする()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();

        /* Execution */
        $response = $this->post($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(302);
        $response->assertRedirect('/auth/login');
    }

    public function test_事務局権限ログイン状態で空のパラメータをPOSTすると何もせず入力画面へリダイレクト()
    {
        /* Preparations */
        $user = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        //事務局権限 role_id:2
        $secretariat = User::factory()->state(['role_id' => 2])->create();
        $this->actingAs($secretariat);
        //通知
        Notification::fake();

        //mock(expectation)
        $this->instance(
            SaveAttachmentInterface::class,
            \Mockery::mock(SaveAttachmentInterface::class, function (MockInterface $mock) {
                //保存処理が呼び出されないこと
                $mock->shouldNotReceive('__invoke');
            })
        );

        /* Execution */
        $response = $this->post($this->route($apply->id), []);

        /* Assertions */
        $response->assertStatus(302);
        $response->assertRedirect('/attachment/apply/secretariat/show/' . $apply->id);

        //通知が送信されないことを検証
        Notification::assertNothingSent();
    }

    /**
     * test_正常系2
     *
     * @dataProvider postParameterDataProvider
     * @param array<string> $fileNames
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_正常系2(array $fileNames)
    {
        /* Preparations */
        //データの準備
        $user = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $user])->create();
        $secretariat = User::factory()->state(['role_id' => 2])->create();
        //事務局権限でログイン
        $this->actingAs($secretariat);
        //通知のfake
        Notification::fake();

        //ファイルアップロードのテストのため、画像ファイルっぽいものをパラメタとして生成
        $files = [];
        foreach ($fileNames as $fileName) {
            $files[] = UploadedFile::fake()->image($fileName);
        }

        /* Execution */
        $response = $this->post($this->route($apply->id), ['new' => $files]);

        /* Assertions */
        //ステータス
        $response->assertStatus(302);
        //リダイレクト先
        $response->assertRedirect('/attachment/apply/secretariat/show/' . $apply->id);
        //データベース保存内容の検証
        foreach ($fileNames as $fileName) {
            $this->assertDatabaseHas('attachments', [
                //アップロードしたファイルの元の名前
                'name' => $fileName,
                //対象の申出ID
                'apply_id' => $apply->id,
                //ログイン者（事務局アカウント）のID
                'user_id' => $secretariat->id,
                //固定で、事務局送付資料の番号
                'attachment_type_id' => AttachmentTypes::SECRETARIAT_DOCUMENT
            ]);
        }

        //通知が送信されることのテスト
        Notification::assertSentTo($user, MessageForAddAttachmentBySecretariat::class);
    }

    public function postParameterDataProvider(): array
    {
        return [
            "testcase001_ファイル1つ" => [['file_name001_1']],
            "testcase002_ファイル2つ" => [['file_name002_1', 'file_name002_2']],
        ];
    }

    private function route($applyId): string
    {
        return str_replace('{id}', $applyId, self::Route);
    }
}
