<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Users\Domain\UserActivation;
use App\Users\Domain\UserActivationRepository as UserActivationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class UserActivationRepository implements UserActivationRepositoryInterface
{
    public function __construct(private ManagerRegistry $managerRegistry, private EntityManagerInterface $entityManager)
    {
    }

    public function findActivationById(UuidV4 $id): ?UserActivation
    {
        $query = $this->entityManager->createQuery('
            SELECT ac 
            FROM App\Users\Domain\UserActivation ac 
            WHERE ac.id = :id AND ac.usedAt IS NULL AND ac.cancelledAt IS NULL
        ');
        $query = $query->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Exception) {
            return null;
        }
    }

    public function addActivation(UserActivation $activation): void
    {
        $this->manager()->persist($activation);
    }

    public function findActiveActivationByUserId(UuidV4 $userId): ?UserActivation
    {
        return $this->managerRegistry->getRepository(UserActivation::class)->findOneBy([
            'userId' => $userId,
            'cancelledAt' => null,
            'usedAt' => null,
        ]);
    }

    private function manager(): ObjectManager
    {
        /**
         * @var ObjectManager
         */
        $manager = $this->managerRegistry->getManagerForClass(UserActivation::class);

        return $manager;
    }
}
