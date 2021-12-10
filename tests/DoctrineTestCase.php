<?php

namespace App\Tests;

use App\Core\Domain\Clock;
use App\Core\Domain\Email;
use App\Core\Domain\SystemId;
use App\Laboratory\Domain\Laboratory;
use App\Laboratory\Domain\LaboratoryRepository;
use App\MedicalTests\Domain\TestsResultRepository;
use App\Users\Application\UseCase\ActivateUser;
use App\Users\Application\UseCase\CreateUser;
use App\Users\Domain\UserActivationRepository;
use App\Users\Domain\UserRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

class DoctrineTestCase extends KernelTestCase
{
    /**
     * @group legacy
     */
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        /**
         * @var ManagerRegistry $doctrine
         */
        $doctrine = self::$container->get('doctrine');

        (new ORMPurger($doctrine->getManager()))->purge();
    }

    protected function messageBus(): MessageBusInterface
    {
        $container = self::$container;

        return $container->get(MessageBusInterface::class);
    }

    protected function connection(): Connection
    {
        $container = self::$container;

        return $container->get('doctrine.dbal.default_connection');
    }

    protected function entityManager(): EntityManager
    {
        $container = self::$container;

        return $container->get('doctrine')->getManager();
    }

    protected function clock(): Clock
    {
        $container = self::$container;

        return $container->get(Clock::class);
    }

    protected function userPasswordHasher(): UserPasswordHasherInterface
    {
        return static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    protected function userRepository(): UserRepository
    {
        return static::getContainer()->get(UserRepository::class);
    }

    protected function userActivationRepository(): UserActivationRepository
    {
        return static::getContainer()->get(UserActivationRepository::class);
    }

    protected function laboratoryRepository(): LaboratoryRepository
    {
        return static::getContainer()->get(LaboratoryRepository::class);
    }

    protected function createActiveUser(
        UuidV4 $id,
        Email $email,
        array $roles,
        UuidV4 $activationTokenId,
        string $firstName = 'Kacper',
        string $lastName = 'testerski',
        UuidV4 $createdBy = null,
        string $pesel = '54102377645',
        string $gender = 'MALE',
        ?UuidV4 $laboratoryId = null
    ): UuidV4
    {
        $command = new CreateUser\Command(
            $id,
            $firstName,
            $lastName,
            $email,
            $roles ?: ['ROLE_PATIENT'],
            $createdBy ?? SystemId::asUuidV4(),
            $pesel,
            $gender,
            $activationTokenId
        );

        if ($laboratoryId) {
            $command->setLaboratoryId($laboratoryId);
        }

        $this->messageBus()->dispatch($command);

        $this->messageBus()->dispatch(new ActivateUser\Command($activationTokenId));

        return $id;
    }

    protected function createInactiveUser(
        UuidV4 $id,
        Email $email,
        array $roles,
        UuidV4 $activationTokenId,
        string $firstName = 'Kacper',
        string $lastName = 'testerski',
        UuidV4 $createdBy = null,
        string $pesel = '54102377645',
        string $gender = 'MALE',
        ?UuidV4 $laboratoryId = null
    ): UuidV4
    {
        $command = new CreateUser\Command(
            $id,
            $firstName,
            $lastName,
            $email,
            $roles ?: ['ROLE_PATIENT'],
            $createdBy ?? SystemId::asUuidV4(),
            $pesel,
            $gender,
            $activationTokenId
        );

        if ($laboratoryId) {
            $command->setLaboratoryId($laboratoryId);
        }

        $this->messageBus()->dispatch($command);

        return $id;
    }

    protected function createNewLab(
        UuidV4 $id,
        string $name = 'test lab',
        ?\DateTimeImmutable $createdAt = null,
        ?UuidV4 $createdBy = null
    ): UuidV4
    {
        $date = $this->clock()->currentDateTime();

        $this->laboratoryRepository()->addLaboratory(new Laboratory(
            id: $id,
            name: $name,
            createdAt: $createdAt ?? $date,
            createdBy: SystemId::asUuidV4(),
            updatedAt: $createdAt ?? $date,
            updatedBy: SystemId::asUuidV4(),
        ));

        return $id;
    }

    protected function testsResultRepository(): TestsResultRepository
    {
        return static::getContainer()->get(TestsResultRepository::class);
    }
}
