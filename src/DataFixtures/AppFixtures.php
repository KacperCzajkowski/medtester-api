<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Core\Domain\Clock;
use App\Core\Domain\SystemId;
use App\Laboratory\Domain\Laboratory;
use App\Laboratory\Domain\LaboratoryRepository;
use App\Security\User;
use App\Users\Application\UseCase\ActivateUser;
use App\Users\Application\UseCase\ChangePassword;
use App\Users\Application\UseCase\CreateUser;
use App\Users\Domain\User as DomainUser;
use App\Users\Domain\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

class AppFixtures extends Fixture
{
    private const TEST_PASSWORD = 'test1234';
    private array $laboratoriesIds = [
        'ac178621-2389-4a5f-8681-bd4f44444140',
        '7b8ed30a-e477-41fe-b629-1282198dd298',
    ];

    public function __construct(
        private MessageBusInterface $messageBus,
        private ManagerRegistry $managerRegistry,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private LaboratoryRepository $laboratoryRepository,
        private Clock $clock
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->addPatients();
        $this->addLaboratories();
        $this->addLabWorkers();
        $manager->flush();
    }

    private function addPatients(): void
    {
        // active
        $this->messageBus->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'kacper@patient.pl',
            roles: ['ROLE_PATIENT'],
            createdBy: SystemId::asUuidV4(),
            pesel: '62100784114',
            gender: 'MALE',
            activationTokenId: $activationTokenId = UuidV4::v4()
        ));
        $this->setDefaultPasswordForUserWithId($id);
        $this->activateUserByTokenId($activationTokenId);

        $this->messageBus->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Krystyna',
            lastName: 'Czarkowska',
            email: 'krystyna@patient.pl',
            roles: ['ROLE_PATIENT'],
            createdBy: SystemId::asUuidV4(),
            pesel: '95102971668',
            gender: 'FEMALE',
            activationTokenId: $activationTokenId = UuidV4::v4()
        ));
        $this->setDefaultPasswordForUserWithId($id);
        $this->activateUserByTokenId($activationTokenId);

        // inactive
        $this->messageBus->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Michał',
            lastName: 'Jakis',
            email: 'michal@patient.pl',
            roles: ['ROLE_PATIENT'],
            createdBy: SystemId::asUuidV4(),
            pesel: '95022694252',
            gender: 'MALE',
            activationTokenId: UuidV4::v4()
        ));
        $this->setDefaultPasswordForUserWithId($id);

        $this->messageBus->dispatch(new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Martyna',
            lastName: 'Jakastam',
            email: 'martyna@patient.pl',
            roles: ['ROLE_PATIENT'],
            createdBy: SystemId::asUuidV4(),
            pesel: '61050145123',
            gender: 'FEMALE',
            activationTokenId: UuidV4::v4()
        ));
        $this->setDefaultPasswordForUserWithId($id);
    }

    private function addLabWorkers(): void
    {
        // active
        $command = new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Kacper',
            lastName: 'Czajkowski',
            email: 'kacper@lab.pl',
            roles: ['ROLE_LABORATORY_WORKER'],
            createdBy: SystemId::asUuidV4(),
            pesel: '04251763985',
            gender: 'MALE',
            activationTokenId: $activationTokenId = UuidV4::v4()
        );
        $command->setLaboratoryId(Uuidv4::fromString($this->laboratoriesIds[0]));
        $this->messageBus->dispatch($command);
        $this->setDefaultPasswordForUserWithId($id);
        $this->activateUserByTokenId($activationTokenId);

        $command = new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Krystyna',
            lastName: 'Czarkowska',
            email: 'krystyna@lab.pl',
            roles: ['ROLE_LABORATORY_WORKER'],
            createdBy: SystemId::asUuidV4(),
            pesel: '89041555928',
            gender: 'FEMALE',
            activationTokenId: $activationTokenId = UuidV4::v4()
        );
        $command->setLaboratoryId(Uuidv4::fromString($this->laboratoriesIds[1]));
        $this->messageBus->dispatch($command);
        $this->setDefaultPasswordForUserWithId($id);
        $this->activateUserByTokenId($activationTokenId);

        // inactive
        $command = new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Michał',
            lastName: 'Jakis',
            email: 'michal@lab.pl',
            roles: ['ROLE_LABORATORY_WORKER'],
            createdBy: SystemId::asUuidV4(),
            pesel: '99083026438',
            gender: 'MALE',
            activationTokenId: UuidV4::v4()
        );
        $command->setLaboratoryId(Uuidv4::fromString($this->laboratoriesIds[0]));
        $this->messageBus->dispatch($command);
        $this->setDefaultPasswordForUserWithId($id);

        $command = new CreateUser\Command(
            id: $id = UuidV4::v4(),
            firstName: 'Martyna',
            lastName: 'Jakastam',
            email: 'martyna@lab.pl',
            roles: ['ROLE_LABORATORY_WORKER'],
            createdBy: SystemId::asUuidV4(),
            pesel: '74121836686',
            gender: 'FEMALE',
            activationTokenId: UuidV4::v4()
        );
        $command->setLaboratoryId(Uuidv4::fromString($this->laboratoriesIds[1]));
        $this->messageBus->dispatch($command);
        $this->setDefaultPasswordForUserWithId($id);
    }

    private function connection(): Connection
    {
        /**
         * @var Connection $conn
         */
        $conn = $this->managerRegistry->getConnection();

        return $conn;
    }

    private function setDefaultPasswordForUserWithId(UuidV4 $id): void
    {
        /**
         * @var DomainUser $user
         */
        $user = $this->userRepository->findUserById($id);
        $this->messageBus->dispatch(new ChangePassword\Command(
            userId: $id,
            hashedNewPassword: $this->passwordHasher->hashPassword(
                new User($user),
                self::TEST_PASSWORD
            ),
        ));
    }

    private function activateUserByTokenId(UuidV4 $tokenId): void
    {
        $this->messageBus->dispatch(new ActivateUser\Command($tokenId->toRfc4122()));
    }

    private function addLaboratories(): void
    {
        $this->laboratoryRepository->addLaboratory(new Laboratory(
            id: UuidV4::fromString($this->laboratoriesIds[0]),
            name: 'Labo namber one',
            createdAt: $this->clock->currentDateTime(),
            createdBy: SystemId::asUuidV4(),
            updatedAt: $this->clock->currentDateTime(),
            updatedBy: SystemId::asUuidV4()
        ));

        $this->laboratoryRepository->addLaboratory(new Laboratory(
            id: UuidV4::fromString($this->laboratoriesIds[1]),
            name: 'Labo drugie w kolejce',
            createdAt: $this->clock->currentDateTime(),
            createdBy: SystemId::asUuidV4(),
            updatedAt: $this->clock->currentDateTime(),
            updatedBy: SystemId::asUuidV4()
        ));
    }
}
