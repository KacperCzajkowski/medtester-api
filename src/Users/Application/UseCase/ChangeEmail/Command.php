<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\ChangeEmail;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private UuidV4 $userId,
        private string $newEmail
    ) {
    }

    public function userId(): UuidV4
    {
        return $this->userId;
    }

    public function newEmail(): Email
    {
        return new Email($this->newEmail);
    }
}
