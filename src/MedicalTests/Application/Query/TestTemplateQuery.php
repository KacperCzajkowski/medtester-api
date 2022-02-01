<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query;

use App\MedicalTests\Application\Query\TestTemplateQuery\TestTemplateDetails;

interface TestTemplateQuery
{
    /**
     * @return TestTemplateDetails[]
     */
    public function fetchAll(): array;
}
