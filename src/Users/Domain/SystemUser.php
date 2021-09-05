<?php

namespace App\Users\Domain;

use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class SystemUser
{
    private ?string $password;

    public function __construct(
        private UuidV4 $id,
        private string $firstName,
        private string $lastName,
        private Pesel $pesel,
        private string $email,
        private \DateTimeImmutable $createdAt,
        private UuidV4 $createdBy,
        private \DateTimeImmutable $updatedAt,
        private UuidV4 $updatedBy
    ) {}
}
