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

namespace App\Console\Commands;

use App\Console\Handlers\NotificationTestHandler;
use App\Console\Parameters\NotificationTestParameter;
use GitBalocco\LaravelUiCli\CliCommand;
use GitBalocco\LaravelUiCli\Contract\CliCommandInterface;
use GitBalocco\LaravelUiCli\Contract\CliHandlerInterface;
use Ncc01\Notification\Application\Usecase\SendStartCreatingDocumentInterface;
use Ncc01\Notification\Application\Usecase\SendStartPriorConsultationNotificationInterface;

/**
 * Class NotificationTestCommand
 * @package App\Console\Commands
 * @method NotificationTestParameter getCliParameter()
 * @method NotificationTestHandler getCliHandler()
 */
class NotificationTestCommand extends CliCommand implements CliCommandInterface
{
    /** @var string $signature The name and signature of the console command. */
    protected $signature = 'notification:test';

    /** @var string $description The console command description. */
    protected $description = '通知のテスト';

    /** @var string $parameterClassName CliParameterInterfaceを実装した、引数の管理を担当するクラス名。 */
    protected $parameterClassName = NotificationTestParameter::class;


    public function createCliHandler(
        SendStartPriorConsultationNotificationInterface $sendStartPriorConsultationNotification,
        SendStartCreatingDocumentInterface $sendStartCreatingDocument
    ): CliHandlerInterface {
        return new NotificationTestHandler(
            $sendStartPriorConsultationNotification,
            $sendStartCreatingDocument
        );
    }

    /**
     * initCliCommand
     * @return void
     */
    public function initCliCommand(): void
    {
        // 以下にCliCommandの初期化処理を実装してください。
        // このメソッドは、引数でメソッドインジェクションが可能です。
        // 移譲先のオブジェクトをプロパティにセットする等
        // __construct() handle() の代わりに、このメソッド内でCliCommandクラスの初期化を実施するコードを実装してください。
        // 初期化処理が不要な場合、このメソッドは削除して構いません。
    }

    /**
     * handle
     * @return int
     */
    public function handle(): int
    {
        $handler = $this->getCliHandler();
        $handler->__invoke();
        return 0;
    }
}
