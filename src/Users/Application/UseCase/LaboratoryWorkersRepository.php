<?php

namespace App\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Users\Domain\LaboratoryWorker;
use Symfony\Component\Uid\UuidV4;

interface LaboratoryWorkersRepository
{
    public function findLaboratoryWorkerByEmailAndLaboratoryId(Email $email, UuidV4 $laboratoryId): ?LaboratoryWorker;

    public function addWorker(LaboratoryWorker $worker): void;
}
