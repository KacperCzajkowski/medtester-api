<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Core\Domain\Clock;
use App\Core\Domain\SystemId;
use App\Laboratory\Domain\Laboratory;
use App\Laboratory\Domain\LaboratoryRepository;
use App\MedicalTests\Domain\Indicator;
use App\MedicalTests\Domain\ReferenceRange;
use App\MedicalTests\Domain\TestTemplate;
use App\MedicalTests\Domain\TestTemplateRepository;
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
        private TestTemplateRepository $templateRepository,
        private Clock $clock
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->addPatients();
        $this->addLaboratories();
        $this->addLabWorkers();
        $this->addTemplates();
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

    private function addTemplates(): void
    {
        $tmp = new TestTemplate(
            UuidV4::v4(),
            'Morfologia',
            'ICD-9: C55',
            new \DateTimeImmutable(),
            [
                new Indicator(
                    'Leukocyty',
                    0,
                    'tys./μl',
                    new ReferenceRange(
                        4.20,
                        9.00
                    )
                ),
                new Indicator(
                    'Erytrocyty',
                    0,
                    'mln./μl',
                    new ReferenceRange(
                        4.60,
                        6.10
                    )
                ),
                new Indicator(
                    'Hemoglobina',
                    0,
                    'g/dl',
                    new ReferenceRange(
                        13.70,
                        17.50
                    )
                ),
            ]
        );

        $this->templateRepository->add($tmp);

        $tmp = new TestTemplate(
            UuidV4::v4(),
            'Badanie ogólne moczu',
            'ICD-9: A01',
            new \DateTimeImmutable(),
            [
                new Indicator(
                    'Ciężar właściwy',
                    0,
                    'g/ml',
                    new ReferenceRange(
                        1.015,
                        1.03
                    )
                ),
                new Indicator(
                    'pH',
                    0,
                    '-',
                    new ReferenceRange(
                        4.80,
                        7.40
                    )
                ),
            ]
        );

        $this->templateRepository->add($tmp);

        $tmp = new TestTemplate(
            UuidV4::v4(),
            'Próba wątrobowa',
            'ICD-10: R94.5',
            new \DateTimeImmutable(),
            [
                new Indicator(
                    'Bilirubina całkowita (BIL)',
                    0,
                    'mg/dl',
                    new ReferenceRange(
                        0.0,
                        1.1
                    )
                ),
                new Indicator(
                    'Aminotransferaza alaninowa (ALT)',
                    0,
                    'U/I',
                    new ReferenceRange(
                        10.0,
                        31.0
                    )
                ),
                new Indicator(
                    'Aminotransferaza asparaginowa (AST)',
                    0,
                    'U/I',
                    new ReferenceRange(
                        10.0,
                        37.0
                    )
                ),
                new Indicator(
                    'Aminotransferaza asparaginowa (AST)',
                    0,
                    'U/I',
                    new ReferenceRange(
                        0.0,
                        115.0
                    )
                ),
            ]
        );

        $this->templateRepository->add($tmp);
    }
}
