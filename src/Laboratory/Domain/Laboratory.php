<?php

declare(strict_types=1);

namespace App\Laboratory\Domain;

use Symfony\Component\Uid\UuidV4;

class Laboratory
{
    public function __construct(
        private UuidV4 $id,
        private string $name,
        private \DateTimeImmutable $createdAt,
        private UuidV4 $createdBy,
        private \DateTimeImmutable $updatedAt,
        private UuidV4 $updatedBy
    ) {
    }
}
