<?php

namespace Tests\Unit\Packages\User\Application\UsecaseInteractor;

use App\Models\Apply;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Ncc01\Apply\Application\Gateway\ApplyRepositoryInterface;
use Ncc01\User\Application\Service\ValidatePermission;
use Ncc01\User\Application\Usecase\ValidatePermissionParameters\ValidateDeleteAttachmentParameter;
use Ncc01\User\Application\UsecaseInteractor\ValidatePermissionDeleteAttachment;
use Tests\TestCase;

/**
 * @coversDefaultClass \Ncc01\User\Application\UsecaseInteractor\ValidatePermissionDeleteAttachment
 */
class ValidatePermissionDeleteAttachmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test_2つの引数がどちらもNULLでない場合例外が発生する
     * @covers ::__invoke
     * @covers ::checkArguments
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_2つの引数がどちらもNULLでない場合例外が発生する()
    {
        /** @var ValidatePermissionDeleteAttachment $targetClass */
        $targetClass = App::make(ValidatePermissionDeleteAttachment::class);
        $this->expectException(\LogicException::class);
        $targetClass->__invoke(1, 1);
    }

    /**
     * test_2つの引数がどちらもNULLである場合例外が発生する
     * @covers ::__invoke
     * @covers ::checkArguments
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_2つの引数がどちらもNULLである場合例外が発生する()
    {
        /** @var ValidatePermissionDeleteAttachment $targetClass */
        $targetClass = App::make(ValidatePermissionDeleteAttachment::class);
        $this->expectException(\LogicException::class);
        $targetClass->__invoke(null, null);
    }

    /**
     * test_attachmentIdを与えた場合、Attachmentに紐づいたApplyがチェック対象となる
     * memo:factoryで作られるのはModel、findByIdで取得できるのはEntityであるため、
     * setApply、setAttachmentの引数チェックは行っていない
     * @covers ::__invoke
     * @covers ::withAttachmentId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_attachmentIdを与えた場合、Attachmentに紐づいたApplyがチェック対象となる()
    {
        /* Preparation */
        //申出者
        $applicantUser = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $applicantUser])->create();
        $attachment = Attachment::factory()->state(['user_id' => $applicantUser, 'apply_id' => $apply])->create();

        //mock1
        $mockValidatePermission = \Mockery::mock(ValidatePermission::class);
        $mockValidatePermission->shouldReceive('__invoke')->once();

        //mock2
        $mockApplyRepository = \Mockery::mock(ApplyRepositoryInterface::class);
        //attachment に紐付けられた apply_id が操作対象になっていることのExpectation
        $mockApplyRepository->shouldReceive('findById')->with($attachment->apply_id)->once();

        /** @var ValidatePermissionDeleteAttachment $targetClass */
        $targetClass = App::make(
            ValidatePermissionDeleteAttachment::class,
            [
                'applyRepository' => $mockApplyRepository,
                'validatePermissionService' => $mockValidatePermission,
            ]
        );
        $targetClass->__invoke(null, $attachment->id);
    }

    /**
     * test_applyIdを与えた場合、そのApplyがチェック対象となる
     *
     * @covers ::__invoke
     * @covers ::withApplyId
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     */
    public function test_applyIdを与えた場合、そのApplyがチェック対象となる()
    {
        /* Preparation */
        //申出者
        $applicantUser = User::factory()->state(['role_id' => 3])->create();
        $apply = Apply::factory()->state(['user_id' => $applicantUser])->create();


        //mock1
        $mockValidatePermission = \Mockery::mock(ValidatePermission::class);
        $mockValidatePermission->shouldReceive('__invoke')->once();

        //mock2
        $mockApplyRepository = \Mockery::mock(ApplyRepositoryInterface::class);
        //attachment に紐付けられた apply_id が操作対象になっていることのExpectation
        $mockApplyRepository->shouldReceive('findById')->with($apply->id)->once();

        //mock3
        $mockValidateDeleteAttachmentParameter = \Mockery::mock(ValidateDeleteAttachmentParameter::class);
        $mockValidateDeleteAttachmentParameter->shouldReceive('setAttachment')->with(null);
        $mockValidateDeleteAttachmentParameter->allows('setApply');


        /** @var ValidatePermissionDeleteAttachment $targetClass */
        $targetClass = App::make(
            ValidatePermissionDeleteAttachment::class,
            [
                'applyRepository' => $mockApplyRepository,
                'validatePermissionService' => $mockValidatePermission,
                'validateDeleteAttachmentParameter' => $mockValidateDeleteAttachmentParameter
            ]
        );
        $targetClass->__invoke($apply->id, null);
    }
}
