<?php

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Security\User;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\ChangePassword;
use Symfony\Component\Uid\UuidV4;

class ChangePasswordTest extends DoctrineTestCase
{
    private UuidV4 $id;

    public function setUp(): void
    {
        parent::setUp();

        $this->id = $this->createUser(UuidV4::v4(), new Email('test@test.pl'), ['ROLE_PATIENT'], UuidV4::v4());
    }

    public function testUserSuccessfullyChangedPassword(): void
    {
        $user = $this->userRepository()->findUserById($this->id);
        $passwordBeforeChange = $user->password();

        $this->messageBus()->dispatch(new ChangePassword\Command(
            userId: $this->id,
            hashedNewPassword: $this->userPasswordHasher()->hashPassword(new User($user), $newPassword = 'test1234')
        ));

        $result = $this->connection()->fetchAssociative('SELECT password from users where id = :id', ['id' => $this->id]);

        static::assertNotEquals($passwordBeforeChange, $result['password']);
        static::assertEquals($newPassword, $result['password']);
    }
}
