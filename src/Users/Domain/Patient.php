<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class Patient extends SystemUser
{
    public function __construct(
        UuidV4 $id,
        string $firstName,
        string $lastName,
        Email $email,
        \DateTimeImmutable $createdAt,
        UuidV4 $createdBy,
        \DateTimeImmutable $updatedAt,
        UuidV4 $updatedBy,
        private Pesel $pesel,
        private string $gender
    ) {
        parent::__construct(
            $id,
            $firstName,
            $lastName,
            $email,
            $createdAt,
            $createdBy,
            $updatedAt,
            $updatedBy
        );
    }
}
