<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Core\Domain\SystemId;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\ActivateUser;
use App\Users\Application\UseCase\CreateUser;
use Symfony\Component\Uid\UuidV4;

class ActivateUserTest extends DoctrineTestCase
{
    private UuidV4 $tokenId;
    private UuidV4 $userId;

    public function setUp(): void
    {
        parent::setUp();

        $this->messageBus()->dispatch(new CreateUser\Command(
            id: $this->userId = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'test2@test.pl',
            roles: ['ROLE_PATIENT'],
            createdBy:  SystemId::asUuidV4(),
            pesel: '54102377645',
            gender: 'MALE',
            activationTokenId: $this->tokenId = UuidV4::v4()
        ));
    }

    public function testUserWillBeActivatedSuccessfullyWhenTokenExistsAndIsNotUsed(): void
    {
        $this->messageBus()->dispatch(new ActivateUser\Command($this->tokenId->toRfc4122()));

        $result = $this->connection()->fetchAssociative('
            SELECT * 
            FROM users_activation 
            WHERE id = :id
        ', [
            'id' => $this->tokenId->toRfc4122()
        ]);

        $user = $this->userRepository()->findUserById($this->userId);

        static::assertNotNull($user);
        static::assertTrue($user->isActive());
        static::assertNotNull($result['used_at']);
    }
}