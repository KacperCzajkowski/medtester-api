<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Core\Domain\SystemId;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\ChangeEmail;
use App\Users\Application\UseCase\CreateUser;
use Symfony\Component\Uid\UuidV4;

class ChangeEmailTest extends DoctrineTestCase
{
    private UuidV4 $userId;

    public function setUp(): void
    {
        parent::setUp();

        $this->createActiveUser(
            $this->userId = UuidV4::v4(),
            new Email('test@test.pl'),
            ['ROLE_PATIENT'],
            UuidV4::v4()
        );
    }

    public function testUserWillChangeEmailSuccessfully(): void
    {
        $this->messageBus()->dispatch(new ChangeEmail\Command(
            $this->userId,
            $newEmail = 'po.zmianie@test.pl'
        ));

        $user = $this->userRepository()->findUserById($this->userId);

        $activations = $this->connection()->fetchAllAssociative('
            SELECT *
            FROM users_activation
            WHERE user_id = :userId AND used_at IS NULL AND cancelled_at IS NULL
        ', [
            'userId' => $this->userId->toRfc4122()
        ]);

        static::assertTrue($user->email()->isEquals(new Email($newEmail)));
        static::assertFalse($user->isActive());
        static::assertNotEmpty($activations);
    }

    public function testUserWillChangeEmailSuccessfullyEvenIfIsNotActive(): void
    {
        $this->messageBus()->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'test2@test.pl',
            roles: ['ROLE_PATIENT'],
            createdBy:  SystemId::asUuidV4(),
            pesel: '54102377645',
            gender: 'MALE',
            activationTokenId: $firstTokenId = UuidV4::v4()
        ));

        $this->messageBus()->dispatch(new ChangeEmail\Command(
            $id,
            $newEmail = 'po.zmianie@test.pl'
        ));

        $user = $this->userRepository()->findUserById($id);

        $cancelledActivation = $this->connection()->fetchAssociative('
            SELECT *
            FROM users_activation
            WHERE user_id = :userId AND cancelled_at IS NOT NULL
        ', [
            'userId' => $id->toRfc4122()
        ]);

        static::assertNotFalse($cancelledActivation);
        static::assertEquals($firstTokenId->toRfc4122(), $cancelledActivation['id']);

        $activations = $this->connection()->fetchAssociative('
            SELECT *
            FROM users_activation
            WHERE user_id = :userId AND used_at IS NULL AND cancelled_at IS NULL
        ', [
            'userId' => $id->toRfc4122()
        ]);

        static::assertTrue($user->email()->isEquals(new Email($newEmail)));
        static::assertFalse($user->isActive());
        static::assertNotFalse($activations);
    }
}