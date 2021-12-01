<?php

declare(strict_types=1);

namespace App\Users\Application\Exception;

use Symfony\Component\Uid\UuidV4;

class ActionNotAllowedException extends \LogicException
{
    public static function byNotHavingNeededPermissions(UuidV4 $userId): ActionNotAllowedException
    {
        return new ActionNotAllowedException(
            sprintf('User with id %s is not allowed to do this operation.', $userId->toRfc4122())
        );
    }
}
