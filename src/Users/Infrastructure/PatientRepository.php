<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Core\Domain\Email;
use App\Users\Application\UseCase\PatientRepository as PatientRepositoryInterface;
use App\Users\Domain\Patient;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class PatientRepository implements PatientRepositoryInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
    ) {
    }

    public function findPatientByEmail(Email $email): ?Patient
    {
        return $this->managerRegistry
            ->getRepository(Patient::class)
            ->findOneBy([
                'email' => $email,
        ]);
    }

    public function addPatient(Patient $patient): void
    {
        /**
         * @var ObjectManager $objectManager
         */
        $objectManager = $this->managerRegistry->getManagerForClass(Patient::class);
        $objectManager->persist($patient);
    }
}
