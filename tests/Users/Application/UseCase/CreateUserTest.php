<?php

namespace App\Tests\Users\Application\UseCase;

use App\Core\Domain\SystemId;
use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\CreateUser;
use Pesel\Pesel;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\UuidV4;
use App\Users\Domain\User;

class CreateUserTest extends DoctrineTestCase
{
    private UuidV4 $labId;

    public function setUp(): void
    {
        parent::setUp();

        $this->labId = $this->createNewLab(UuidV4::v4());
    }

    public function testPatientUserWillBeCreatedSuccessfully(): void
    {
        $this->messageBus()->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'test@test.pl',
            roles: $roles = ['ROLE_PATIENT'],
            createdBy: SystemId::asUuidV4(),
            pesel: '84071592871',
            gender: User::GENDER['MALE'],
            activationTokenId: UuidV4::v4()
        ));

        $result = $this->connection()->fetchAssociative('
            SELECT * 
            FROM users
            WHERE id = :id
        ', [
            'id' => $id
        ]);

        self::assertNotFalse($result);
        self::assertEquals($roles, json_decode($result['roles'], true));
    }

    public function testLabWorkerWillBeCreatedWhenCommandWillContainAllNeededInformation(): void
    {
        $command = new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'test@test.pl',
            roles: $roles = [User::ROLES['ROLE_LABORATORY_WORKER']],
            createdBy: SystemId::asUuidV4(),
            pesel: '84071592871',
            gender: User::GENDER['MALE'],
            activationTokenId: UuidV4::v4()
        );

        $command->setLaboratoryId($this->labId);

        $this->messageBus()->dispatch($command);

        $result = $this->connection()->fetchAssociative('
            SELECT * 
            FROM users
            WHERE id = :id
        ', [
            'id' => $id
        ]);

        self::assertNotFalse($result);
        self::assertEquals($roles, json_decode($result['roles'], true, 512, JSON_THROW_ON_ERROR));
    }

    public function testLaboratoryWorkerWillNotBeCreatedWhenLaboratoryIdIsNull(): void
    {
        $this->expectException(HandlerFailedException::class);

        $this->messageBus()->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'test@test.pl',
            roles: [User::ROLES['ROLE_LABORATORY_WORKER']],
            createdBy: SystemId::asUuidV4(),
            pesel: '84071592871',
            gender: User::GENDER['MALE'],
            activationTokenId: UuidV4::v4()
        ));
    }
}
