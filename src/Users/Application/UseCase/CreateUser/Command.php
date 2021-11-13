<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\CreateUser;

use App\Core\Domain\Email;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class Command
{
    private ?UuidV4 $laboratoryId = null;

    /**
     * @param string[] $roles
     */
    public function __construct(
        private UuidV4 $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private array $roles,
        private UuidV4 $createdBy,
        private string $pesel,
        private string $gender
    ) {
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }

    public function createdBy(): UuidV4
    {
        return $this->createdBy;
    }

    public function laboratoryId(): ?UuidV4
    {
        return $this->laboratoryId;
    }

    public function id(): UuidV4
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): Email
    {
        return new Email($this->email);
    }

    public function pesel(): Pesel
    {
        return new Pesel($this->pesel);
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function setLaboratoryId(UuidV4 $laboratoryId): void
    {
        $this->laboratoryId = $laboratoryId;
    }
}
