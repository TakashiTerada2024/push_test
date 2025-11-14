<?php

namespace Tests\Unit\Packages\Notification\Application\UsecaseInteractor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendStartCreatingDocumentParameterInterface;
use Ncc01\Notification\Application\UsecaseInteractor\SendStartCreatingDocument;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\Notification\Application\UsecaseInteractor\SendStartCreatingDocument
 */
class SendStartCreatingDocumentTest extends TestCase
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
        $argumentParameterMock = \Mockery::mock(SendStartCreatingDocumentParameterInterface::class);

        //constructor arguments
        $mockNotificationSender = \Mockery::mock(NotificationSenderInterface::class);
        $mockNotificationSender->shouldReceive('setTargetUserId')->with($argumentUserId)->once()->andReturnSelf();
        $mockNotificationSender->shouldReceive('sendStartCreatingDocument')->with($argumentParameterMock)->once();

        /* execution */
        $object = new SendStartCreatingDocument($mockNotificationSender);
        $object->__invoke($argumentUserId, $argumentParameterMock);
    }
}
