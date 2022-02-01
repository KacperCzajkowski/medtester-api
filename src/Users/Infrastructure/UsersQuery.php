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
            SELECT usrs.email, 
                   usrs.first_name, 
                   usrs.last_name, 
                   usrs.created_at, 
                   usrs.updated_at,
                   usrs.pesel, 
                   usrs.gender,
                   l.id as laboratory_id,
                   l.name as laboratory_name,
                   l.created_at as laboratory_created_at
            FROM
                users usrs LEFT JOIN laboratories l on usrs.laboratory_id = l.id
            WHERE
                usrs.id = :id
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
        $queryBuilder = $this->connection()->createQueryBuilder();

        $queryBuilder
            ->addSelect([
            'u.first_name',
            'u.last_name',
            'u.pesel', ])
            ->from('users', 'u')
            ->andWhere('u.roles::text ILIKE :roles')
            ->setParameter('roles', sprintf('%%%s%%', 'ROLE_PATIENT'));

        $splittedText = explode(' ', $text);

        foreach ($splittedText as $fragment) {
            $queryBuilder
                ->andWhere('
                (first_name ILIKE :text OR 
                   last_name ILIKE :text OR 
                   pesel LIKE :text)')
                ->setParameter('text', sprintf('%%%s%%', $fragment));
        }

        $result = $queryBuilder->execute()->fetchAllAssociative();

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
