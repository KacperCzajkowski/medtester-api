<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Security\User;
use App\Users\Domain\LaboratoryWorker;
use App\Users\Domain\Patient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateLaboratoryWorker implements MessageHandlerInterface
{
    public function __construct(
        private LaboratoryWorkersRepository $workers,
        private Clock $clock,
        private PasswordGenerator $passwordGenerator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(CreateLaboratoryWorker\Command $command): void
    {
        $exists = $this->workers->findWorkerByEmail($command->email());

        if ($exists) {
            throw new EmailAlreadyUsedException($command->email());
        }

        $worker = new LaboratoryWorker(
            $command->id(),
            $command->firstName(),
            $command->lastName(),
            $command->email(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $command->laboratoryId()
        );

        $password = $this->passwordGenerator->getNew();

        $worker->setPassword(
            $this->passwordHasher->hashPassword(new User($worker), $password)
        );

        $this->workers->addWorker($worker);
    }
}
