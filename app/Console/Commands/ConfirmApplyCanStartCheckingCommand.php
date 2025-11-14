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

use App\Console\Handlers\ConfirmApplyCanStartCheckingHandler;
use App\Console\Parameters\ConfirmApplyCanStartCheckingParameter;
use GitBalocco\LaravelUiCli\CliCommand;
use GitBalocco\LaravelUiCli\Contract\CliCommandInterface;
use GitBalocco\LaravelUiCli\Contract\CliHandlerInterface;
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\Usecase\ConfirmApplyCanStartCheckingInterface;

/**
 * Class ConfirmApplyCanStartCheckingCommand
 * @package App\Console\Commands
 * @method ConfirmApplyCanStartCheckingParameter getCliParameter()
 * @method ConfirmApplyCanStartCheckingHandler getCliHandler()
 */
class ConfirmApplyCanStartCheckingCommand extends CliCommand implements CliCommandInterface
{
    /** @var string $signature The name and signature of the console command. */
    protected $signature = 'apply:confirm {id}';

    /** @var string $description The console command description. */
    protected $description = '申出状態のチェックを行う';

    /** @var string $parameterClassName CliParameterInterfaceを実装した、引数の管理を担当するクラス名。 */
    protected $parameterClassName = ConfirmApplyCanStartCheckingParameter::class;

    /**
     * createCliHandler
     * @return CliHandlerInterface
     */
    public function createCliHandler(): CliHandlerInterface
    {
        return new ConfirmApplyCanStartCheckingHandler(
            App::make(ConfirmApplyCanStartCheckingInterface::class),
            $this->getCliParameter()
        );
    }

    /**
     * initCliCommand
     * @return void
     */
    public function initCliCommand(): void
    {
    }

    /**
     * handle
     * @return int
     */
    public function handle(): int
    {
        $handler = $this->getCliHandler();
        $result = $handler->__invoke();

        $this->displayApplyInfo($result);
        $this->line('');
        $this->displayResult($result->isValid());
        $this->line('');
        $this->displayErrorInfo($result->errorMessages());
        $this->line('');

        return 0;
    }


    private function displayResult(bool $result)
    {
        $this->info('Result');
        if ($result) {
            $this->line('OK');
        }
        $this->warn('NG');
    }

    private function displayApplyInfo($result)
    {
        $this->info('Apply Info');

        $this->table(
            ['id', 'status', 'type'],
            [
                [
                    $this->getCliParameter()->getId(),
                    $result->getStatusName(),
                    $result->getTypeName()
                ]
            ]
        );
    }

    private function displayErrorInfo($errorMessages)
    {
        $this->info('Error Info');
        foreach ($errorMessages as $sectionName => $sectionErrors) {
            $this->displayErrorOfSection($sectionName, $sectionErrors);
        }
    }


    private function displayErrorOfSection($sectionName, $sectionErrors)
    {
        $this->line((string)$sectionName);
        foreach ($sectionErrors as $itemName => $errorMessages) {
            $this->line((string)$itemName);
            foreach ($errorMessages as $errorKey => $errorMessage) {
                $this->error((string)$errorKey . ': ' . (string)$errorMessage);
            }
        }
    }
}
