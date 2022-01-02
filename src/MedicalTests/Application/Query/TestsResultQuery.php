<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query;

use App\MedicalTests\Application\Query\TestsResultQuery\EditTestsResultDetails;
use Symfony\Component\Uid\UuidV4;

interface TestsResultQuery
{
    public function findTestsResultInProgressByLabWorkerId(UuidV4 $labWorkerId): ?EditTestsResultDetails;
}
