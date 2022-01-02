<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Users\Application\Query\UsersQuery as UsersQueryInterface;
use App\Users\Application\Query\UsersQuery\BasicUserInfo;
use App\Users\Application\Query\UsersQuery\UserDetails;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class UsersQuery implements UsersQueryInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function fetchUserDetailsById(UuidV4 $id): ?UserDetails
    {
        $result = $this->connection()->fetchAssociative('
            SELECT
                email, first_name, last_name, created_at, updated_at, pesel, gender
            FROM
                users
            WHERE
                id = :id
        ', [
            'id' => $id->toRfc4122(),
        ]);

        if (!$result) {
            return null;
        }

        return UserDetails::fromArray($id->toRfc4122(), $result);
    }

    public function findUsersToTestByText(string $text): array
    {
        $result = $this->connection()->fetchAllAssociative('
            SELECT first_name, last_name, pesel
            FROM users 
            WHERE (first_name ILIKE :text OR 
                   last_name ILIKE :text OR 
                   pesel LIKE :text) AND 
                  roles::text ILIKE :role AND
                  removed_at IS NULL
            ORDER BY created_at
        ', [
            'text' => sprintf('%%%s%%', $text),
            'role' => sprintf('%%%s%%', 'ROLE_PATIENT'),
        ]);

        return array_map(static fn (array $array): BasicUserInfo => BasicUserInfo::fromArray($array), $result);
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
