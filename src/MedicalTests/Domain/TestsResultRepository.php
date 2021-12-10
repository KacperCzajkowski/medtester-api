<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

interface TestsResultRepository
{
    public function fetchTestsResultById(UuidV4 $id): ?TestsResult;

    public function addNewTestsResult(TestsResult $newTestsResult): void;
}