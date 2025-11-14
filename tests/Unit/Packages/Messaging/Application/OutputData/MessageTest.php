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

namespace Tests\Unit\Packages\Messaging\Application\OutputData;

use Carbon\Carbon;
use Ncc01\Messaging\Application\OutputData\Message;
use Ncc01\User\Application\OutputBoundary\AuthenticatedUserInterface;
use Tests\TestCase;

/**
 * MessageTest
 *
 * @package Tests\Unit\Packages\Messaging\Application\OutputData
 * @coversDefaultClass \Ncc01\Messaging\Application\OutputData\Message
 */
class MessageTest extends TestCase
{
    /**
     * test___construct
     *
     * @covers ::__construct
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test___construct()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals('bodyテスト', $targetClass->getBody('bodyテスト'));
    }

    /**
     * test_getBody
     *
     * @covers ::getBody
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getBody()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals('bodyテスト', $targetClass->getBody());
    }

    /**
     * test_setBody
     *
     * @covers ::setBody
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_setBody()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );
        $targetClass->setBody('bodyテスト変更後');

        $this->assertEquals('bodyテスト変更後', $targetClass->getBody('bodyテスト変更後'));
    }

    /**
     * test_getFromId
     *
     * @covers ::getFromId
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getFromId()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(1, $targetClass->getFromId());
    }

    /**
     * test_getId
     *
     * @covers ::getId
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getId()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals('2', $targetClass->getId());
    }

    /**
     * test_getApplyId
     *
     * @covers ::getApplyId
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getApplyId()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(3, $targetClass->getApplyId());
    }

    /**
     * test_getParentId
     *
     * @covers ::getParentId
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getParentId()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(4, $targetClass->getParentId());
    }

    /**
     * test_getFromName
     *
     * @param string $fromName
     * @param string $expectFromName
     * @param bool $isApplicant
     * @param int $timesOfCallIsApplicant
     * @param bool $isSentBySecretariat
     * @param int $timesOfCallIsSentBySecretariat
     * @dataProvider fromNameProvider
     * @covers ::getFromName
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getFromName(
        string $fromName,
        string $expectFromName,
        bool $isApplicant,
        int $timesOfCallIsApplicant,
        bool $isSentBySecretariat,
        int $timesOfCallIsSentBySecretariat
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            $fromName,
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('isSentBySecretariat')
            ->times($timesOfCallIsSentBySecretariat)
            ->andReturn($isSentBySecretariat);

        // AuthenticatedUserInterfaceモック
        $authenticatedUserInterfaceMock = \Mockery::mock(AuthenticatedUserInterface::class);
        $authenticatedUserInterfaceMock
            ->shouldReceive('isApplicant')
            ->times($timesOfCallIsApplicant)
            ->andReturn($isApplicant);

        $this->assertEquals($expectFromName, $messageMock->getFromName($authenticatedUserInterfaceMock));
    }

    /**
     * fromNameProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function fromNameProvider(): array
    {
        return [
            '申請者権限でない場合は担当者名表示' => ['fromNameテスト1', 'fromNameテスト1', false, 1, true, 0],
            '事務局でない場合は担当者名表示' => ['fromNameテスト2', 'fromNameテスト2', true, 1, false, 1],
            '申請者権限でも事務局でもない場合は担当者名表示' => ['fromNameテスト3', 'fromNameテスト3', false, 1, false, 0],
            '申請者権限の事務局の場合は担当者名表示しない' => ['fromNameテスト', '事務局', true, 1, true, 1],
        ];
    }

    /**
     * test_isSentBySecretariat
     *
     * @dataProvider isSentBySecretariatProvider
     * @covers ::isSentBySecretariat
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_isSentBySecretariat(
        int $fromId,
        bool $expectBool
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('getFromId')
            ->times(1)
            ->andReturn($fromId);

        $this->assertEquals($expectBool, $messageMock->isSentBySecretariat());
    }

    /**
     * isSentBySecretariatProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function isSentBySecretariatProvider(): array
    {
        return [
            'idが一致しない場合false' => [1, false],
            'idが一致する場合true' => [2, true],
        ];
    }

    /**
     * test_getCreatedAt
     *
     * @covers ::getCreatedAt
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getCreatedAt()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(new Carbon('2023-07-01'), $targetClass->getCreatedAt());
    }

    /**
     * test_getUpdatedAt
     *
     * @covers ::getUpdatedAt
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getUpdatedAt()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(new Carbon('2023-07-27'), $targetClass->getUpdatedAt());
    }

    /**
     * test_getLastUpdatedAt
     *
     * @covers ::getLastUpdatedAt
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_getLastUpdatedAt()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $this->assertEquals(new Carbon('2023-07-01'), $targetClass->getLastUpdatedAt());
    }

    /**
     * test_setLastUpdatedAt
     *
     * @covers ::setLastUpdatedAt
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_setLastUpdatedAt()
    {
        $targetClass = new Message(
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        );

        $targetClass->setLastUpdatedAt(new Carbon('2023-07-02'));
        $this->assertEquals(new Carbon('2023-07-02'), $targetClass->getLastUpdatedAt());
    }

    /**
     * test_isSentByLoginUser
     *
     * @param bool $expectBool
     * @param int $timesOfCallIsSentBySecretariat
     * @param bool $isSentBySecretariat
     * @param int $timesOfCallFromId
     * @param int $fromId
     * @param int $timesOfCallIsSecretariat
     * @param bool $isSentSecretariat
     * @param int $timesOfCallIsApplicant
     * @param bool $isApplicant
     * @param int $timesOfCallGetId
     * @param int $userId
     * @dataProvider isSentByLoginUserProvider
     * @covers ::isSentByLoginUser
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_isSentByLoginUser(
        bool $expectBool,
        int $timesOfCallIsSentBySecretariat,
        bool $isSentBySecretariat,
        int $timesOfCallFromId,
        int $fromId,
        int $timesOfCallIsSecretariat,
        bool $isSentSecretariat,
        int $timesOfCallIsApplicant,
        bool $isApplicant,
        int $timesOfCallGetId,
        int $userId
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('isSentBySecretariat')
            ->times($timesOfCallIsSentBySecretariat)
            ->andReturn($isSentBySecretariat);
        $messageMock
            ->shouldReceive('getFromId')
            ->times($timesOfCallFromId)
            ->andReturn($fromId);

        // AuthenticatedUserInterfaceモック
        $authenticatedUserInterfaceMock = \Mockery::mock(AuthenticatedUserInterface::class);
        $authenticatedUserInterfaceMock
            ->shouldReceive('isSecretariat')
            ->times($timesOfCallIsSecretariat)
            ->andReturn($isSentSecretariat);
        $authenticatedUserInterfaceMock
            ->shouldReceive('isApplicant')
            ->times($timesOfCallIsApplicant)
            ->andReturn($isApplicant);
        $authenticatedUserInterfaceMock
            ->shouldReceive('getId')
            ->times($timesOfCallGetId)
            ->andReturn($userId);

        $this->assertEquals($expectBool, $messageMock->isSentByLoginUser($authenticatedUserInterfaceMock));
    }

    /**
     * isSentByLoginUserProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function isSentByLoginUserProvider(): array
    {
        return [
            '申請者権限の事務局の場合true' => [true, 1, true, 0, 1, 1, true, 0, false, 0, 1],
            '申出者権限でメッセージ送信者の場合true_申請者権限と事務局が両方false' => [true, 0, false, 1, 1, 1, false, 1, true, 1, 1],
            '申出者権限でメッセージ送信者の場合true_申請者権限がfalse' => [true, 1, false, 1, 1, 1, true, 1, true, 1, 1],
            '申出者権限でメッセージ送信者の場合true_事務局がfalse' => [true, 0, true, 1, 1, 1, false, 1, true, 1, 1],
            '事務局でも申出者でもない場合false' => [false, 0, false, 0, 1, 1, false, 1, false, 0, 1],
        ];
    }

    /**
     * test_canEditUser
     *
     * @param bool $expectBool
     * @param int $timesOfCallIsSentBySecretariat
     * @param bool $isSentBySecretariat
     * @param int $timesOfCanEdit
     * @param bool $canEdit
     * @param int $timesOfCallIsSuperAdmin
     * @param bool $isSuperAdmin
     * @param int $timesOfCallIsSecretariat
     * @param bool $isSentSecretariat
     * @dataProvider canEditUserProvider
     * @covers ::canEditUser
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_canEditUser(
        bool $expectBool,
        int $timesOfCallIsSentBySecretariat,
        bool $isSentBySecretariat,
        int $timesOfCanEdit,
        bool $canEdit,
        int $timesOfCallIsSuperAdmin,
        bool $isSuperAdmin,
        int $timesOfCallIsSecretariat,
        bool $isSentSecretariat
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('isSentBySecretariat')
            ->times($timesOfCallIsSentBySecretariat)
            ->andReturn($isSentBySecretariat);
        $messageMock
            ->shouldReceive('canEdit')
            ->times($timesOfCanEdit)
            ->andReturn($canEdit);

        // AuthenticatedUserInterfaceモック
        $authenticatedUserInterfaceMock = \Mockery::mock(AuthenticatedUserInterface::class);
        $authenticatedUserInterfaceMock
            ->shouldReceive('isSuperAdmin')
            ->times($timesOfCallIsSuperAdmin)
            ->andReturn($isSuperAdmin);
        $authenticatedUserInterfaceMock
            ->shouldReceive('isSecretariat')
            ->times($timesOfCallIsSecretariat)
            ->andReturn($isSentSecretariat);

        $this->assertEquals($expectBool, $messageMock->canEditUser($authenticatedUserInterfaceMock));
    }

    /**
     * canEditUserProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function canEditUserProvider(): array
    {
        return [
            '管理者でも事務局でもない場合false' => [false, 0, false, 0, false, 1, false, 1, false],
            '申請者権限でない場合false' => [false, 1, false, 0, false, 1, true, 0, true],
            '編集不可の場合false' => [false, 1, true, 1, false, 1, true, 0, true],
            'すべて当てはまる場合true' => [true, 1, true, 1, true, 1, true, 0, true],
        ];
    }

    /**
     * test_isDeleted
     *
     * @param bool $expectBool
     * @param string $body
     * @dataProvider isDeletedProvider
     * @covers ::isDeleted
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_isDeleted(
        bool $expectBool,
        string $body
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('getBody')
            ->times(1)
            ->andReturn($body);

        $this->assertEquals($expectBool, $messageMock->isDeleted());
    }

    /**
     * isDeletedProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function isDeletedProvider(): array
    {
        return [
            '本文が削除済み文章と一致する場合true' => [true, '---削除---'],
            '本文が削除済み文章と一致しない場合false' => [false, 'test1'],
        ];
    }

    /**
     * test_canEdit
     *
     * @param bool $expectBool
     * @param int $timesOfCallParentId
     * @param string $parentId
     * @param int $timesOfCallIsDeleted
     * @param bool $isDeleted
     * @dataProvider canEditProvider
     * @covers ::canEdit
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test_canEdit(
        bool $expectBool,
        int $timesOfCallParentId,
        string $parentId = null,
        int $timesOfCallIsDeleted,
        bool $isDeleted
    ) {
        // Messageモック
        $messageMock = \Mockery::mock(Message::class, [
            'bodyテスト',
            1,
            'fromNameテスト',
            new Carbon('2023-07-01'),
            new Carbon('2023-07-27'),
            '2',
            3,
            4
        ])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $messageMock
            ->shouldReceive('getParentId')
            ->times($timesOfCallParentId)
            ->andReturn($parentId);
        $messageMock
            ->shouldReceive('isDeleted')
            ->times($timesOfCallIsDeleted)
            ->andReturn($isDeleted);

        $this->assertEquals($expectBool, $messageMock->canEdit());
    }

    /**
     * canEditProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function canEditProvider(): array
    {
        return [
            'parentIDがない場合false' => [false, 1, '1', 0, false],
            '削除済みの場合false' => [false, 1, null, 1, true],
            'parentIDもなく削除済みでもない場合true' => [true, 1, null, 1, false],
        ];
    }
}
