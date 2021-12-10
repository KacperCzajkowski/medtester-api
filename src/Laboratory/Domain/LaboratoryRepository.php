<?php

declare(strict_types=1);

namespace App\Laboratory\Domain;

use Symfony\Component\Uid\UuidV4;

interface LaboratoryRepository
{
    public function addLaboratory(Laboratory $laboratory): void;

    public function findLaboratoryById(UuidV4 $id): ?Laboratory;
}
