<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\ActivateUser;

use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private string $tokenId
    ) {
    }

    public function tokenId(): UuidV4
    {
        return Uuidv4::fromString($this->tokenId);
    }
}
