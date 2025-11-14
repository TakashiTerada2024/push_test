<?php

namespace Tests\Unit\Packages\Notification\Application\UsecaseInteractor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ncc01\Notification\Application\GatewayInterface\NotificationSenderInterface;
use Ncc01\Notification\Application\InputBoundary\SendCommonMessageParameterInterface;
use Ncc01\Notification\Application\UsecaseInteractor\SendCommonMessageToApplicant;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\Notification\Application\UsecaseInteractor\SendCommonMessageToApplicant
 */
class SendCommonMessageToApplicantTest extends TestCase
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
        $argumentParameterMock = \Mockery::mock(SendCommonMessageParameterInterface::class);

        //constructor arguments
        $mock = \Mockery::mock(NotificationSenderInterface::class);
        $mock->shouldReceive('setTargetUserId')->with($argumentUserId)->once()->andReturnSelf();
        $mock->shouldReceive('sendCommonMessageToApplicant')->with($argumentParameterMock)->once();

        /* execution */
        $object = new SendCommonMessageToApplicant($mock);
        $object->__invoke($argumentUserId, $argumentParameterMock);
    }
}
