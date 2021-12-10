<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Domain\TestsResult;
use App\MedicalTests\Domain\TestsResultRepository as TestsResultRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\UuidV4;

class TestsResultRepository implements TestsResultRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function fetchTestsResultById(UuidV4 $id): ?TestsResult
    {
        return $this->entityManager->getRepository(TestsResult::class)->findOneBy(['id' => $id]);
    }

    public function addNewTestsResult(TestsResult $newTestsResult): void
    {
        $this->entityManager->persist($newTestsResult);
    }
}
