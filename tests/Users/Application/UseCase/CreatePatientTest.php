<?php

namespace App\Tests\Users\Application\UseCase;

use App\Tests\DoctrineTestCase;
use App\Users\Application\UseCase\CreatePatient;
use App\Users\Domain\Patient;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\UuidV4;

class CreatePatientTest extends DoctrineTestCase
{
    public function testNewPatientWillBeCreated(): void
    {
        $this->messageBus()->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Kacper',
            $lastName = 'Czajkowski',
            $password = 'test1234',
            $email = 'kacper@kacper.com',
            UuidV4::v4()->toRfc4122(),
            $pesel = '99111909931',
            $gender = 'male'
        ));

        $result = $this->entityManager()->getRepository(Patient::class)->findAll();

        self::assertCount(1, $result);
    }

    public function testNewPatientWillNotBeCreatedWhenEmailWillBeUsed(): void
    {
        $this->messageBus()->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName = 'Kacper',
            $lastName = 'Czajkowski',
            $password = 'test1234',
            $email = 'kacper@kacper.com',
            UuidV4::v4()->toRfc4122(),
            $pesel = '99111909931',
            $gender = 'male'
        ));

        $result = $this->entityManager()->getRepository(Patient::class)->findAll();

        self::assertCount(1, $result);

        self::expectException(HandlerFailedException::class);
        $this->messageBus()->dispatch(new CreatePatient\Command(
            UuidV4::v4()->toRfc4122(),
            $firstName,
            $lastName,
            $password,
            $email,
            UuidV4::v4()->toRfc4122(),
            $pesel,
            $gender
        ));
    }
}
