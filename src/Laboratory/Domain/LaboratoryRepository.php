<?php

declare(strict_types=1);

namespace App\Laboratory\Domain;

interface LaboratoryRepository
{
    public function addLaboratory(Laboratory $laboratory): void;
}
