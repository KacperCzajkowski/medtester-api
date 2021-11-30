<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\ChangePassword;

use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private UuidV4 $userId,
        private string $hashedNewPassword
    ) {
    }

    public function userId(): UuidV4
    {
        return $this->userId;
    }

    public function hashedNewPassword(): string
    {
        return $this->hashedNewPassword;
    }
}
