<?php

declare(strict_types=1);

namespace App\Users\Application\Exception;

use Symfony\Component\Uid\UuidV4;

class ActivationNotFoundException extends \LogicException
{
    public static function byId(UuidV4 $id): ActivationNotFoundException
    {
        return new ActivationNotFoundException(
            sprintf('User activation with id %s not found', $id->toRfc4122())
        );
    }
}
