<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Security\User;
use App\Users\Domain\Patient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreatePatient implements MessageHandlerInterface
{
    public function __construct(
        private PatientRepository $patientRepository,
        private Clock $clock,
        private PasswordGenerator $passwordGenerator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(CreatePatient\Command $command): void
    {
        $exists = $this->patientRepository->findPatientByEmail($command->email());

        if ($exists) {
            throw new EmailAlreadyUsedException($command->email());
        }

        $patient = new Patient(
            $command->id(),
            $command->firstName(),
            $command->lastName(),
            $command->email(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $command->pesel(),
            $command->gender()
        );

        $password = $this->passwordGenerator->getNew();

        $patient->setPassword(
            $this->passwordHasher->hashPassword(new User($patient), $password)
        );

        $this->patientRepository->addPatient($patient);
    }
}
