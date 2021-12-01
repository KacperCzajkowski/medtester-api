<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\RemoveUser;
use App\Users\Application\UseCase\ResetPassword;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\UuidV4;

class ResetPasswordTest extends DoctrineTestCase
{
    public function testUserWillBeAbleToResetHisPassword(): void
    {
        $this->createActiveUser(
            id: $id = UuidV4::v4(),
            email: $email = new Email('test@test.pl'),
            roles: ['ROLE_PATIENT'],
            activationTokenId: UuidV4::v4()
        );

        $oldPassword = $this->userRepository()->findUserById($id)?->password();

        $this->messageBus()->dispatch(new ResetPassword\Command($email->value()));

        $newPassword = $this->userRepository()->findUserById($id)?->password();

        static::assertNotEquals($oldPassword, $newPassword);
    }

    public function testExceptionWillBeThrownWhenUserDoesNotExist(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessage(
            sprintf('User with email %s not found', $email = 'email@brak.pl')
        );
        $this->messageBus()->dispatch(new ResetPassword\Command($email));
    }
}