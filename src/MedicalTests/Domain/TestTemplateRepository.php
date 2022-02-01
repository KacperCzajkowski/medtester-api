<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

interface TestTemplateRepository
{
    public function findTemplateById(UuidV4 $templateId): ?TestTemplate;

    public function add(TestTemplate $template): void;
}
