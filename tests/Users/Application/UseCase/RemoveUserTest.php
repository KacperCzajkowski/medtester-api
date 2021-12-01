<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\RemoveUser;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\UuidV4;

class RemoveUserTest extends DoctrineTestCase
{
    private UuidV4 $adminId;
    private UuidV4 $activeUserId;
    private UuidV4 $inactiveUserId;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminId = $this->createActiveUser(
            id: UuidV4::v4(),
            email: new Email('admin@test.pl'),
            roles: ['ROLE_SYSTEM_ADMIN'],
            activationTokenId: UuidV4::v4()
        );

        $this->activeUserId = $this->createActiveUser(
            id: UuidV4::v4(),
            email: new Email('active@test.pl'),
            roles: ['ROLE_PATIENT'],
            activationTokenId: UuidV4::v4()
        );

        $this->inactiveUserId = $this->createInactiveUser(
            id: UuidV4::v4(),
            email: new Email('inactive@test.pl'),
            roles: ['ROLE_PATIENT'],
            activationTokenId: UuidV4::v4()
        );
    }

    public function testActiveUserWillBeRemovedSuccessfullyByHimself(): void
    {
        $this->messageBus()->dispatch(new RemoveUser\Command($this->activeUserId, $this->activeUserId));

        $user = $this->userRepository()->findUserById($this->activeUserId);

        static::assertNotNull($user->removedAt());

        $activation = $this->userActivationRepository()->findActiveActivationByUserId($this->activeUserId);

        static::assertNull($activation);
    }

    public function testAdminRemovesActiveUserSuccessfully(): void
    {
        $this->messageBus()->dispatch(new RemoveUser\Command($this->activeUserId, $this->adminId));

        $user = $this->userRepository()->findUserById($this->activeUserId);

        static::assertNotNull($user->removedAt());

        $activation = $this->userActivationRepository()->findActiveActivationByUserId($this->activeUserId);

        static::assertNull($activation);
    }

    public function testAdminRemovesInactiveUserSuccessfully(): void
    {
        $this->messageBus()->dispatch(new RemoveUser\Command($this->inactiveUserId, $this->adminId));

        $user = $this->userRepository()->findUserById($this->inactiveUserId);

        static::assertNotNull($user->removedAt());

        $activation = $this->userActivationRepository()->findActiveActivationByUserId($this->inactiveUserId);

        static::assertNull($activation);
    }

    public function testNotAdminWillTryToRemoveUser(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessage(
            sprintf('User with id %s is not allowed to do this operation.', $this->activeUserId->toRfc4122())
        );
        $this->messageBus()->dispatch(new RemoveUser\Command($this->inactiveUserId, $this->activeUserId));
    }

    public function testNotExistingUserWillTryToRemoveUser(): void
    {
        $id = UuidV4::v4();
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessage(
            sprintf('User with id %s was not found', $id->toRfc4122())
        );
        $this->messageBus()->dispatch(new RemoveUser\Command($this->inactiveUserId, $id));
    }
}