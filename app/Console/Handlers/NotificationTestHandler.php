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

namespace App\Console\Handlers;

use App\Http\Requests\Apply\MessageBodyDto;
use GitBalocco\LaravelUiCli\CliHandler;
use GitBalocco\LaravelUiCli\Contract\CliHandlerInterface;
use Illuminate\Support\Facades\App;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendRemandCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartSubmittingDocumentParameterInterface;
use Ncc01\Notification\Application\InputData\SendStartPriorConsultationParameter;
use Ncc01\Notification\Application\Usecase\SendCommonMessageToApplicantInterface;
use Ncc01\Notification\Application\Usecase\SendRemandCheckingDocumentInterface;
use Ncc01\Notification\Application\Usecase\SendStartCheckingDocumentInterface;
use Ncc01\Notification\Application\Usecase\SendStartCreatingDocumentInterface;
use Ncc01\Notification\Application\Usecase\SendStartPriorConsultationNotificationInterface;
use Ncc01\Notification\Application\Usecase\SendStartSubmittingDocumentInterface;

/**
 * Class NotificationTestHandler
 * @package App\Console\Handlers
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NotificationTestHandler extends CliHandler implements CliHandlerInterface
{
    public function __construct(
        private SendStartPriorConsultationNotificationInterface $sendStartPriorConsultationNotification,
        private SendStartCreatingDocumentInterface $sendStartCreatingDocument,
    ) {
    }

    public function __invoke()
    {
        $this->send1();
        sleep(1);   //メッセージ表示画面で作成時刻を1秒ずつ変えるため
        $this->send2();
        sleep(1);
        $this->send3();
        sleep(1);
        $this->send4();
        sleep(1);
        $this->send5();
        sleep(1);
        $this->send6();
    }


    private function send1()
    {
        $dto = new MessageBodyDto();
        $dto->setApplyType(1);
        $dto->setSubject('件名');
        $dto->setPurposeOfUse('利用目的');
        $dto->setResearchMethod('方法');
        $dto->setNeedToUse('必要性');
        $dto->setApplicantName('氏名');
        $dto->setApplicantNameKana('ナマエ');
        $dto->setAffiliation('所属');
        $dto->setApplicantPhoneNumber('電話番号');

        $parameter = new SendStartPriorConsultationParameter();


        $parameter->setApplyId(1);
        $parameter->setSenderUserId(101);
        $parameter->setSenderUserName('送信者のお名前');
        $parameter->setDto($dto);
        $this->sendStartPriorConsultationNotification->__invoke($parameter);
    }

    private function send2()
    {
        /** @var SendStartCreatingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartCreatingDocumentParameterInterface::class);
        $parameter->setApplyId(1);
        $parameter->setSenderUserId(2);
        $parameter->setSenderUserName('事務局');
        $this->sendStartCreatingDocument->__invoke(101, $parameter);
    }

    private function send3()
    {
        /** @var SendStartCheckingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartCheckingDocumentParameterInterface::class);
        $parameter->setApplyId(1);
        $parameter->setSenderUserId(101);
        $parameter->setSenderUserName('申請者の名前');

        /** @var SendStartCheckingDocumentInterface $usecase */
        $usecase = App::make(SendStartCheckingDocumentInterface::class);
        $usecase->__invoke($parameter);
    }

    private function send4()
    {
        /** @var SendStartSubmittingDocumentParameterInterface $parameter */
        $parameter = App::make(SendStartSubmittingDocumentParameterInterface::class);
        $parameter->setApplyId(1);
        $parameter->setSenderUserId(2);
        $parameter->setSenderUserName('事務局');

        /** @var SendStartSubmittingDocumentInterface $usecase */
        $usecase = App::make(SendStartSubmittingDocumentInterface::class);
        $usecase->__invoke(101, $parameter);
    }

    private function send5()
    {
        /** @var SendRemandCheckingDocumentParameterInterface $parameter */
        $parameter = App::make(SendRemandCheckingDocumentParameterInterface::class);
        $parameter->setApplyId(1);
        $parameter->setSenderUserId(2);
        $parameter->setSenderUserName('事務局');

        /** @var SendRemandCheckingDocumentInterface $usecase */
        $usecase = App::make(SendRemandCheckingDocumentInterface::class);
        $usecase->__invoke(101, $parameter);
    }

    private function send6()
    {
        /** @var SendCommonMessageParameterInterface $parameter */
        $parameter = App::make(SendCommonMessageParameterInterface::class);
        $parameter->setApplyId(1);
        $parameter->setSenderUserName('事務局');
        $parameter->setMessageBody('テスト：事務局から申請者へのメッセージ本文です。');

        /** @var SendCommonMessageToApplicantInterface $usecase */
        $usecase = App::make(SendCommonMessageToApplicantInterface::class);
        $usecase->__invoke(101, $parameter);
    }
}
