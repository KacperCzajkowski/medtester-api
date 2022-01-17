<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Domain\ExtendedTestsResultDetails;
use App\MedicalTests\Domain\LaboratoryDetails;
use App\MedicalTests\Domain\TestsResult;
use App\MedicalTests\Domain\TestsResultRepository as TestsResultRepositoryInterface;
use App\MedicalTests\Domain\UserDetails;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
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

    public function fetchTestsResultInProgressByLabWorkerId(UuidV4 $id): ?TestsResult
    {
        return $this->entityManager->getRepository(TestsResult::class)->findOneBy([
            'laboratoryWorkerId' => $id,
            'status' => 'in-progress',
        ]);
    }

    public function getExtendedTestsResultDetails(UuidV4 $id): ExtendedTestsResultDetails
    {
        $test = $this->fetchTestsResultById($id);

        if (!$test) {
            throw new InvalidArgumentException();
        }

        $userDetails = $this->entityManager->getConnection()->fetchAssociative('
            SELECT first_name as user_first_name,
                   last_name as user_last_name,
                   email as user_email,
                   pesel as user_pesel,
                   gender as user_gender
            FROM users 
            WHERE id = :userId
        ', ['userId' => $test->userId()->toRfc4122()]);

        if (!$userDetails) {
            throw new InvalidArgumentException();
        }

        $labDetails = $this->entityManager->getConnection()->fetchAssociative('
            SELECT u.id as lab_worker_id,
                   u.first_name as lab_worker_first_name,
                   u.last_name as lab_worker_last_name,
                   l.id as lab_id,
                   l.name as lab_name
            FROM users u JOIN laboratories l on l.id = u.laboratory_id
            WHERE u.id = :labWorkerId
        ', ['labWorkerId' => $test->laboratoryWorkerId()->toRfc4122()]);

        if (!$labDetails) {
            throw new InvalidArgumentException();
        }

        return new ExtendedTestsResultDetails(
            $id,
            $test->createdAt(),
            LaboratoryDetails::fromArray($labDetails),
            UserDetails::fromArray($userDetails),
            $test->results()
        );
    }
}
