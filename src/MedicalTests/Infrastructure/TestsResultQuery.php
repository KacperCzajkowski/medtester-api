<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Application\Query\TestsResultQuery as TestsResultQueryInterface;
use App\MedicalTests\Application\Query\TestsResultQuery\EditTestsResultDetails;
use App\MedicalTests\Application\Query\TestsResultQuery\TestsResultDetails;
use App\MedicalTests\Application\Query\TestsResultQuery\UserResult;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class TestsResultQuery implements TestsResultQueryInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function findTestsResultInProgressByLabWorkerId(UuidV4 $labWorkerId): ?EditTestsResultDetails
    {
        $result = $this->connection()->fetchAssociative('
            SELECT 
                   u.id as user_id,
                   u.email as email,
                   u.first_name as first_name,
                   u.last_name as last_name,
                   u.pesel as pesel,
                   u.gender as gender,
                   tr.results as results
            FROM tests_results tr 
                INNER JOIN users u ON  tr.user_id = u.id
            WHERE
                laboratory_worker_id = :labWorkerId AND status = :inProgressStatus
        ', [
            'labWorkerId' => $labWorkerId->toRfc4122(),
            'inProgressStatus' => 'in-progress',
        ]);

        if (!$result) {
            return null;
        }

        return EditTestsResultDetails::fromArray($result);
    }

    public function fetchAllTestsResultsForUser(UuidV4 $userId): array
    {
        $results = $this->connection()->fetchAllAssociative('
            SELECT tr.id,
                   tr.created_at,
                   jsonb_array_length(tr.results) as tests_count,
                   u.first_name as laboratory_worker_first_name,
                   u.last_name as laboratory_worker_last_name,
                   l.name as laboratory_name
            FROM tests_results tr 
                INNER JOIN users u ON tr.laboratory_worker_id = u.id 
                INNER JOIN laboratories l ON u.laboratory_id = l.id
            WHERE tr.user_id = :userId AND tr.status != :inProgressStatus
        ', [
            'userId' => $userId->toRfc4122(),
            'inProgressStatus' => 'in-progress',
        ]);

        return array_map(static fn (array $result): UserResult => UserResult::fromArray($result), $results);
    }

    public function fetchResultById(UuidV4 $id): ?TestsResultDetails
    {
        $result = $this->connection()->fetchAssociative('
            SELECT tr.id,
                   tr.created_at,
                   tr.updated_at,
                   tr.results,
                   u.id as lab_worker_id,
                   u.first_name as lab_worker_first_name,
                   u.last_name as lab_worker_last_name,
                   l.id as lab_id,
                   l.name as lab_name
            FROM tests_results tr INNER JOIN users u on tr.laboratory_worker_id = u.id
                LEFT JOIN laboratories l on u.laboratory_id = l.id
        ');

        if (!$result) {
            return null;
        }

        return TestsResultDetails::fromArray($result);
    }

    private function connection(): Connection
    {
        /**
         * @var Connection $connection
         */
        $connection = $this->managerRegistry->getConnection();

        return $connection;
    }
}
