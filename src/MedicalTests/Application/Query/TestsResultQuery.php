<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query;

use App\MedicalTests\Application\Query\TestsResultQuery\EditTestsResultDetails;
use App\MedicalTests\Application\Query\TestsResultQuery\TestsResultDetails;
use Symfony\Component\Uid\UuidV4;

interface TestsResultQuery
{
    public function findTestsResultInProgressByLabWorkerId(UuidV4 $labWorkerId): ?EditTestsResultDetails;

    public function fetchAllTestsResultsForUser(UuidV4 $userId): array;

    public function fetchResultById(UuidV4 $id): ?TestsResultDetails;
}
