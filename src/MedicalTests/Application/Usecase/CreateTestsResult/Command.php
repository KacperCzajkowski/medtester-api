<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase\CreateTestsResult;

use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private string $id,
        private string $creatorId,
        private string $userPesel
    ) {
    }

    public function id(): UuidV4
    {
        return UuidV4::fromString($this->id);
    }

    public function creatorId(): UuidV4
    {
        return UuidV4::fromString($this->creatorId);
    }

    public function userPesel(): Pesel
    {
        return new Pesel($this->userPesel);
    }
}
