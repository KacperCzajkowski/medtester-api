<?php

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Domain\TestTemplate;
use App\MedicalTests\Domain\TestTemplateRepository as TestTemplateRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\UuidV4;

class TestTemplateRepository implements TestTemplateRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}
    public function findTemplateById(UuidV4 $templateId): ?TestTemplate
    {
        return $this->entityManager->getRepository(TestTemplate::class)->findOneBy([
            'id' => $templateId
        ]);
    }

    public function add(TestTemplate $template): void
    {
        $this->entityManager->persist($template);
    }
}