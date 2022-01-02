<?php

declare(strict_types=1);

namespace App\Core\Domain\Exceptions;

use App\Core\Domain\Email;
use Exception;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class UserNotFoundException extends Exception
{
    public static function byId(UuidV4 $id): UserNotFoundException
    {
        return new UserNotFoundException(
            sprintf('User with id %s was not found', $id->toRfc4122())
        );
    }

    public static function byEmail(Email $email): UserNotFoundException
    {
        return new UserNotFoundException(
            sprintf('User with email %s not found', $email->value())
        );
    }

    public static function byPesel(Pesel $userPesel): UserNotFoundException
    {
        return new UserNotFoundException(
            sprintf('User with pesel %s not found', $userPesel->__toString())
        );
    }
}
