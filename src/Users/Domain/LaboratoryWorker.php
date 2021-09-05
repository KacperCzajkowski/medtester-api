<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

class LaboratoryWorker extends SystemUser
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
        private UuidV4 $laboratoryId,
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
