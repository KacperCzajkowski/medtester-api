<?php

declare(strict_types=1);

namespace App\Core\Domain\Exceptions;

use Symfony\Component\Uid\UuidV4;

class UserNotFoundException extends \Exception
{
    public static function byId(UuidV4 $id): UserNotFoundException
    {
        return new UserNotFoundException(
            sprintf('User with id %s was not found', $id->toRfc4122())
        );
    }
}
