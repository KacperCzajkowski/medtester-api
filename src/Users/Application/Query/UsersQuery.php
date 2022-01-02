<?php

declare(strict_types=1);

namespace App\Users\Application\Query;

use App\Users\Application\Query\UsersQuery\BasicUserInfo;
use App\Users\Application\Query\UsersQuery\UserDetails;
use Symfony\Component\Uid\UuidV4;

interface UsersQuery
{
    public function fetchUserDetailsById(UuidV4 $id): ?UserDetails;

    /**
     * @return BasicUserInfo[]
     */
    public function findUsersToTestByText(string $text): array;
}
