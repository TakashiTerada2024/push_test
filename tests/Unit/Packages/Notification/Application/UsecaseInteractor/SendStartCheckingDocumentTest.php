<?php

namespace Tests\Unit\Packages\Notification\Application\UsecaseInteractor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCheckingDocumentParameterInterface;
use Ncc01\Notification\Application\UsecaseInteractor\SendStartCheckingDocument;
use Ncc01\User\Enterprise\AllSecretariatUser;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\Notification\Application\UsecaseInteractor\SendStartCheckingDocument
 */
class SendStartCheckingDocumentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test
     * @covers ::__construct
     * @covers ::__invoke
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test___invoke()
    {
        /* preparations */
        //method arguments
        $argumentParameterMock = \Mockery::mock(SendStartCheckingDocumentParameterInterface::class);

        //constructor arguments
        $mockAllSecretariatUser = \Mockery::mock(AllSecretariatUser::class);
        $mockAllSecretariatUser->shouldReceive('getId')->andReturn(999);

        $mockNotificationSender = \Mockery::mock(NotificationSenderInterface::class);
        $mockNotificationSender->shouldReceive('setTargetUserId')->with(999)->once()->andReturnSelf();
        $mockNotificationSender->shouldReceive('sendStartCheckingDocument')->with($argumentParameterMock)->once();

        /* execution */
        $object = new SendStartCheckingDocument($mockNotificationSender, $mockAllSecretariatUser);
        $object->__invoke($argumentParameterMock);
    }
}
