<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\RemoveUser;

use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(private UuidV4 $userId, private UuidV4 $requestedBy)
    {
    }

    public function userId(): UuidV4
    {
        return $this->userId;
    }

    public function requestedBy(): UuidV4
    {
        return $this->requestedBy;
    }
}
