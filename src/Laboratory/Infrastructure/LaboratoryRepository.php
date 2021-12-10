<?php

declare(strict_types=1);

namespace App\Laboratory\Infrastructure;

use App\Laboratory\Domain\Laboratory;
use App\Laboratory\Domain\LaboratoryRepository as LaboratoryRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class LaboratoryRepository implements LaboratoryRepositoryInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function addLaboratory(Laboratory $laboratory): void
    {
        /**
         * @var ObjectManager $manager
         */
        $manager = $this->managerRegistry->getManagerForClass(Laboratory::class);

        $manager->persist($laboratory);
        $manager->flush(); // todo w przypadku dodawania usecase'ow na dodawanie laba trzeba to wyrzucic
    }

    public function findLaboratoryById(UuidV4 $id): ?Laboratory
    {
        return $this->managerRegistry->getRepository(Laboratory::class)->findOneBy(['id' => $id]);
    }
}
