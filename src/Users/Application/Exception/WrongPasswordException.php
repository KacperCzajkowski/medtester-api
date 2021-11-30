<?php

declare(strict_types=1);

namespace App\Users\Application\Exception;

use Symfony\Component\Uid\UuidV4;

class WrongPasswordException extends \Exception
{
    public static function byUserId(UuidV4 $userId): WrongPasswordException
    {
        return new WrongPasswordException(
            sprintf('Password for %s is incorrect', $userId->toRfc4122())
        );
    }
}
