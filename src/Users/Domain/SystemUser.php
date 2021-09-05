<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

class SystemUser
{
    private string $password;

    public function __construct(
        private UuidV4 $id,
        private string $firstName,
        private string $lastName,
        private Email $email,
        private \DateTimeImmutable $createdAt,
        private UuidV4 $createdBy,
        private \DateTimeImmutable $updatedAt,
        private UuidV4 $updatedBy
    ) {
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
