<?php

namespace App\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Users\Domain\LaboratoryWorker;
use Symfony\Component\Uid\UuidV4;

interface LaboratoryWorkersRepository
{
    public function findWorkerByEmail(Email $email): ?LaboratoryWorker;

    public function addWorker(LaboratoryWorker $worker): void;
}
