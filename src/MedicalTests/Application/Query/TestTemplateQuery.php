<?php

namespace App\MedicalTests\Application\Query;

use App\MedicalTests\Application\Query\TestTemplateQuery\TestTemplateDetails;

interface TestTemplateQuery
{
    /**
     * @return TestTemplateDetails[]
     */
    public function fetchAll(): array;
}