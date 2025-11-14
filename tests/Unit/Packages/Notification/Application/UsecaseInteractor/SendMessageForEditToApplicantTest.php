<?php

namespace Tests\Unit\Packages\Notification\Application\UsecaseInteractor;

use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendMessageForEditParameterInterface;
use Ncc01\Notification\Application\UsecaseInteractor\SendMessageForEditToApplicant;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\Notification\Application\UsecaseInteractor\SendMessageForEditToApplicant
 */
class SendMessageForEditToApplicantTest extends TestCase
{
    /**
     * test___invoke
     * @covers ::__construct
     * @covers ::__invoke
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test___invoke()
    {
        /* preparations */
        //method arguments
        $argumentUserId = 1;
        $argumentParameterMock = \Mockery::mock(SendMessageForEditParameterInterface::class);

        //constructor arguments
        $mock = \Mockery::mock(NotificationSenderInterface::class);
        $mock->shouldReceive('setTargetUserId')->with($argumentUserId)->once()->andReturnSelf();
        $mock->shouldReceive('sendMessageForEditToApplicant')->with($argumentParameterMock)->once();

        /* execution */
        $targetObject = new SendMessageForEditToApplicant($mock);
        $targetObject->__invoke($argumentUserId, $argumentParameterMock);
    }
}
