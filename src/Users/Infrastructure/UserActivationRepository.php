<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Users\Domain\UserActivation;
use App\Users\Domain\UserActivationRepository as UserActivationRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class UserActivationRepository implements UserActivationRepositoryInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function findActivationById(UuidV4 $id): ?UserActivation
    {
        return $this->managerRegistry->getRepository(UserActivation::class)->findOneBy(['id' => $id]);
    }

    public function addActivation(UserActivation $activation): void
    {
        $this->manager()->persist($activation);
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
