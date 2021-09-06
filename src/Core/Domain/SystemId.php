<?php

namespace App\Core\Domain;

use Exception;
use Symfony\Component\Uid\UuidV4;

final class SystemId
{
    const ID = '906f5e6b-795e-4bfd-b87b-6837d6ae4048';

    public static function asUuidV4(): UuidV4
    {
        return UuidV4::fromString(self::ID);
    }

    public static function asString(): string
    {
        return self::ID;
    }
}
