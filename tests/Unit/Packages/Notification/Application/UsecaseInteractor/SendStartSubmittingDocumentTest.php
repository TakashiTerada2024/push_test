<?php

namespace Tests\Unit\Packages\Notification\Application\UsecaseInteractor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartSubmittingDocumentParameterInterface;
use Ncc01\Notification\Application\UsecaseInteractor\SendStartSubmittingDocument;
use Ncc01\User\Enterprise\AllSecretariatUser;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\Notification\Application\UsecaseInteractor\SendStartSubmittingDocument
 */
class SendStartSubmittingDocumentTest extends TestCase
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
        $argumentUserId = 1;
        $argumentParameterMock = \Mockery::mock(SendStartSubmittingDocumentParameterInterface::class);

        //constructor arguments
        $mockAllSecretariatUser = \Mockery::mock(AllSecretariatUser::class);

        $mockNotificationSender = \Mockery::mock(NotificationSenderInterface::class);
        $mockNotificationSender->shouldReceive('setTargetUserId')->with($argumentUserId)->once()->andReturnSelf();
        $mockNotificationSender->shouldReceive('sendStartSubmittingDocument')->with($argumentParameterMock)->once();

        /* execution */
        $object = new SendStartSubmittingDocument($mockNotificationSender, $mockAllSecretariatUser);
        $object->__invoke($argumentUserId, $argumentParameterMock);
    }
}
