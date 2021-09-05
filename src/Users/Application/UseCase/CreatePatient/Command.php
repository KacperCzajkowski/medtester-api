<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\CreatePatient;

use App\Core\Domain\Email;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $password,
        private string $email,
        private string $createdBy,
        private string $pesel,
        private string $gender
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

    public function pesel(): Pesel
    {
        return new Pesel($this->pesel);
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function password(): string
    {
        return $this->password;
    }
}
