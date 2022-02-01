<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Application\Query\TestTemplateQuery as TestTemplateQueryInterface;
use App\MedicalTests\Application\Query\TestTemplateQuery\TestTemplateDetails;
use Doctrine\ORM\EntityManagerInterface;

class TestTemplateQuery implements TestTemplateQueryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function fetchAll(): array
    {
        $result = $this->entityManager->getConnection()->fetchAllAssociative('
            SELECT id, name, icd_code
            FROM tests_templates
        ');

        return array_map(static fn (array $tmp): TestTemplateDetails => TestTemplateDetails::fromArray($tmp), $result);
    }
}
