<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Users\Domain\Patient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatePatient implements MessageHandlerInterface
{
    public function __construct(
        private PatientRepository $patientRepository,
        private Clock $clock
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

        $patient->setPassword($command->password());

        $this->patientRepository->addPatient($patient);
    }
}
