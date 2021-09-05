<?php

namespace App\Core\Domain;

use Exception;
use Symfony\Component\Uid\UuidV4;

final class SystemId
{
    const ID = '906f5e6b-795e-4bfd-b87b-6837d6ae4048';

    public static function generateNew(): UuidV4
    {
        return SystemId::fromString(self::ID);
    }

    /**
     * @throws Exception
     */
    public static function fromString(string $uuid): UuidV4
    {
        if (self::ID !== $uuid) {
            throw new Exception('You cannot create system id with other id');
        }

        return UuidV4::fromString($uuid);
    }
}
