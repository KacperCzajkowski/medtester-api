<?php

declare(strict_types=1);

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\ActivateUser;
use Symfony\Component\Uid\UuidV4;

class ActivateUserTest extends DoctrineTestCase
{
    private UuidV4 $tokenId;
    private UuidV4 $userId;

    public function setUp(): void
    {
        parent::setUp();

        $this->userId = $this->createUser(
            UuidV4::v4(),
            new Email('test@test.pl'),
            ['ROLE_PATIENT'],
            $this->tokenId = UuidV4::v4()
        );
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