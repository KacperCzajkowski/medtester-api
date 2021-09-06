<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Users\Domain\LaboratoryWorker;
use App\Users\Domain\Patient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateLaboratoryWorker implements MessageHandlerInterface
{
    public function __construct(
        private LaboratoryWorkersRepository $workers,
        private Clock $clock
    ) {
    }

    public function __invoke(CreateLaboratoryWorker\Command $command): void
    {
        $exists = $this->workers->findLaboratoryWorkerByEmailAndLaboratoryId(
            $command->email(),
            $command->laboratoryId()
        );

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

        $worker->setPassword($command->password());

        $this->workers->addWorker($worker);
    }
}
