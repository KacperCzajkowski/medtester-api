<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Core\Domain\SystemId;
use App\Users\Application\UseCase\CreatePatient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\UuidV4;

class AppFixtures extends Fixture
{
    public function __construct(private MessageBusInterface $messageBus) {}

    public function load(ObjectManager $manager): void
    {
        $this->addPatients();
        $manager->flush();
    }

    private function addPatients(): void
    {
        $this->messageBus->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Kacper',
            $lastName = 'Czajkowski',
            $password = 'test1234',
            $email = 'kacper@kacper.pl',
            SystemId::asString(),
            $pesel = '62100784114',
            $gender = 'male'
        ));

        $this->messageBus->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Krystyna',
            $lastName = 'Czajkowski',
            $password = 'test1234',
            $email = 'krystyna@krystyna.pl',
            SystemId::asString(),
            $pesel = '95102971668',
            $gender = 'female'
        ));

        $this->messageBus->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Karol',
            $lastName = 'Jakistamski',
            $password = 'test1234',
            $email = 'karol@karol.pl',
            SystemId::asString(),
            $pesel = '95022694252',
            $gender = 'male'
        ));

        $this->messageBus->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Kamila',
            $lastName = 'Jakastamska',
            $password = 'test1234',
            $email = 'kamila@kamila.pl',
            SystemId::asString(),
            $pesel = '61050145123',
            $gender = 'female'
        ));

        $this->messageBus->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Marcel',
            $lastName = 'Turbokozacki',
            $password = 'test1234',
            $email = 'marcel@marcel.pl',
            SystemId::asString(),
            $pesel = '70111012193',
            $gender = 'male'
        ));
    }
}
