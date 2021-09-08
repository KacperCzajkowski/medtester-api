<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Core\Domain\Email;
use App\Users\Application\UseCase\LaboratoryWorkersRepository as LaboratoryWorkersRepositoryInterface;
use App\Users\Application\UseCase\PatientRepository as PatientRepositoryInterface;
use App\Users\Domain\LaboratoryWorker;
use App\Users\Domain\Patient;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class LaboratoryWorkersRepository implements LaboratoryWorkersRepositoryInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
    ) {
    }

    public function findLaboratoryWorkerByEmailAndLaboratoryId(Email $email, UuidV4 $laboratoryId): ?LaboratoryWorker
    {
        return $this->managerRegistry
            ->getRepository(LaboratoryWorker::class)
            ->findOneBy([
                'email' => $email,
                'laboratoryId' => $laboratoryId
            ]);
    }

    public function addWorker(LaboratoryWorker $worker): void
    {
        /**
         * @var ObjectManager $objectManager
         */
        $objectManager = $this->managerRegistry->getManagerForClass(LaboratoryWorker::class);
        $objectManager->persist($worker);
    }
}
