<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Application\Query\TestsResultQuery as TestsResultQueryInterface;
use App\MedicalTests\Application\Query\TestsResultQuery\EditTestsResultDetails;
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

    private function connection(): Connection
    {
        /**
         * @var Connection $connection
         */
        $connection = $this->managerRegistry->getConnection();

        return $connection;
    }
}
