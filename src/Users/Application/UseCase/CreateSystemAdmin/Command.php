<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\CreateSystemAdmin;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $createdBy,
    ) {
    }

    public function email(): Email
    {
        return new Email($this->email);
    }

    public function id(): UuidV4
    {
        return new UuidV4($this->id);
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function createdBy(): UuidV4
    {
        return new UuidV4($this->createdBy);
    }
}
