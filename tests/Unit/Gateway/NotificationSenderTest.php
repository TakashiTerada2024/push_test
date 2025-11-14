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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace Tests\Unit\Gateway;

use Illuminate\Support\Facades\App;
use App\Gateway\NotificationSender;
use App\Http\Requests\Apply\MessageBodyDto;
use App\Models\User;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendMessageForEditParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendRemandCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartPriorConsultationParameterInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartSubmittingDocumentParameterInterface;
use Tests\TestCase;

/**
 * NotificationSenderTest
 *
 * @package Tests\Unit\Gateway
 * @coversDefaultClass \App\Gateway\NotificationSender
 */
class NotificationSenderTest extends TestCase
{
    /**
     * test_sendStartPriorConsultation
     * @covers ::sendStartPriorConsultation
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendStartPriorConsultation()
    {
        // SendStartPriorConsultationParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendStartPriorConsultationParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(1);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(11);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test1');
        $interfaceMock->shouldReceive('getDto')
            ->once()
            ->andReturn(App::make(MessageBodyDto::class));

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(1);
        $targetClass->sendStartPriorConsultation($interfaceMock);
    }

    /**
     * test_sendStartCreatingDocument
     * @covers ::sendStartCreatingDocument
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendStartCreatingDocument()
    {
        // SendStartCreatingDocumentParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendStartCreatingDocumentParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(2);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(22);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test2');

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(2);
        $targetClass->sendStartCreatingDocument($interfaceMock);
    }

    /**
     * test_sendStartCheckingDocument
     * @covers ::sendStartCheckingDocument
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendStartCheckingDocument()
    {
        // SendStartCheckingDocumentParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendStartCheckingDocumentParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(3);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(33);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test3');

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(3);
        $targetClass->sendStartCheckingDocument($interfaceMock);
    }

    /**
     * test_sendStartSubmittingDocument
     * @covers ::sendStartSubmittingDocument
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendStartSubmittingDocument()
    {
        // SendStartSubmittingDocumentParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendStartSubmittingDocumentParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(4);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(44);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test4');

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(4);
        $targetClass->sendStartSubmittingDocument($interfaceMock);
    }

    /**
     * test_sendRemandCheckingDocument
     * @covers ::sendRemandCheckingDocument
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendRemandCheckingDocument()
    {
        // SendRemandCheckingDocumentParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendRemandCheckingDocumentParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(5);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(55);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test5');

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(5);
        $targetClass->sendRemandCheckingDocument($interfaceMock);
    }

    /**
     * test_sendCommonMessageToApplicant
     * @covers ::sendCommonMessageToApplicant
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendCommonMessageToApplicant()
    {
        // SendCommonMessageParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendCommonMessageParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(6);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(6);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test6');
        $interfaceMock->shouldReceive('getMessageBody')
            ->once()
            ->andReturn('testMessage6');

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(6);
        $targetClass->sendCommonMessageToApplicant($interfaceMock);
    }

    /**
     * test_sendMessageForEditToApplicant
     * @covers ::sendMessageForEditToApplicant
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_sendMessageForEditToApplicant()
    {
        // SendMessageForEditParameterInterfaceモック
        $interfaceMock = \Mockery::mock(SendMessageForEditParameterInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $interfaceMock->shouldReceive('getApplyId')
            ->once()
            ->andReturn(7);
        $interfaceMock->shouldReceive('getSenderUserId')
            ->once()
            ->andReturn(77);
        $interfaceMock->shouldReceive('getSenderUserName')
            ->once()
            ->andReturn('test7');
        $interfaceMock->shouldReceive('getMessageBody')
            ->once()
            ->andReturn('testMessage7');
        $interfaceMock->shouldReceive('getNotificationId')
            ->once()
            ->andReturn(777);

        // Userモデルモック
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('findOrFail')
            ->once()
            ->andReturnSelf();
        $userMock->shouldReceive('notify')
            ->once()
            ->andReturn();

        App::shouldReceive('make')
            ->with(User::class)
            ->once()
            ->andReturn($userMock);

        $targetClass = new NotificationSender();
        $targetClass->setTargetUserId(7);
        $targetClass->sendMessageForEditToApplicant($interfaceMock);
    }
}
