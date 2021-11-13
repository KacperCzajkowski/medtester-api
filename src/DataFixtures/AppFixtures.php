<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const TEST_PASSWORD = 'test1234';

    public function __construct(
        private MessageBusInterface $messageBus,
        private ManagerRegistry $managerRegistry,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->addPatients();
        $this->addLabWorkers();
        $manager->flush();
    }

    private function addPatients(): void
    {
//        $this->messageBus->dispatch(new CreatePatient\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Kacper',
//            $lastName = 'Czajkowski',
//            $email = 'kacper@patient.pl',
//            SystemId::asString(),
//            $pesel = '62100784114',
//            $gender = 'male'
//        ));
//
//        $this->messageBus->dispatch(new CreatePatient\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Krystyna',
//            $lastName = 'Czajkowski',
//            $email = 'krystyna@patient.pl',
//            SystemId::asString(),
//            $pesel = '95102971668',
//            $gender = 'female'
//        ));
//
//        $this->messageBus->dispatch(new CreatePatient\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Karol',
//            $lastName = 'Jakistamski',
//            $email = 'karol@patient.pl',
//            SystemId::asString(),
//            $pesel = '95022694252',
//            $gender = 'male'
//        ));
//
//        $this->messageBus->dispatch(new CreatePatient\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Kamila',
//            $lastName = 'Jakastamska',
//            $email = 'kamila@patient.pl',
//            SystemId::asString(),
//            $pesel = '61050145123',
//            $gender = 'female'
//        ));
//
//        $this->messageBus->dispatch(new CreatePatient\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Marcel',
//            $lastName = 'Turbokozacki',
//            $email = 'marcel@patient.pl',
//            SystemId::asString(),
//            $pesel = '70111012193',
//            $gender = 'male'
//        ));
    }

    private function addLabWorkers(): void
    {
//        $labId = UuidV4::v4();
//
//        $this->messageBus->dispatch(new CreateLaboratoryWorker\Command(
//            ($id = UuidV4::v4())->toRfc4122(),
//            $firstName = 'Kacper',
//            $lastName = 'Czajkowski',
//            $email = 'kacper@worker.pl',
//            SystemId::asString(),
//            $labId->toRfc4122()
//        ));
//
//        $this->connection()->executeQuery('
//            UPDATE users
//            SET password = :password
//            WHERE id = :id
//        ', [
//            'id' => $id,
//            'password' => $this->passwordHasher->hashPassword(
//                new User($this->laboratoryWorkersRepository->findWorkerByEmail(new Email($email))),
//                self::TEST_PASSWORD
//            ),
//        ]);
//
//        $this->messageBus->dispatch(new CreateLaboratoryWorker\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Sebastian',
//            $lastName = 'Miliński',
//            $email = 'sebal@worker.pl',
//            SystemId::asString(),
//            $labId->toRfc4122()
//        ));
//
//        $this->messageBus->dispatch(new CreateLaboratoryWorker\Command(
//            UuidV4::v4()->toRfc4122(),
//            $firstName = 'Konrad',
//            $lastName = 'Nigla',
//            $email = 'nigla@worker.pl',
//            SystemId::asString(),
//            $labId->toRfc4122()
//        ));
    }

    private function connection(): Connection
    {
        /**
         * @var Connection $conn
         */
        $conn = $this->managerRegistry->getConnection();

        return $conn;
    }
}
