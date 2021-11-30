<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class User
{
    const GENDER = [
        'MALE' => 'MALE',
        'FEMALE' => 'FEMALE',
    ];

    const ROLES = [
        'ROLE_PATIENT' => 'ROLE_PATIENT',
        'ROLE_LABORATORY_WORKER' => 'ROLE_LABORATORY_WORKER',
        'ROLE_SYSTEM_ADMIN' => 'ROLE_SYSTEM_ADMIN',
    ];

    private ?string $password;
    private ?UuidV4 $laboratoryId = null;

    /**
     * @param string[] $roles
     */
    public function __construct(
        private UuidV4 $id,
        private string $firstName,
        private string $lastName,
        private Email $email,
        private array $roles,
        private \DateTimeImmutable $createdAt,
        private UuidV4 $createdBy,
        private \DateTimeImmutable $updatedAt,
        private UuidV4 $updatedBy,
        private Pesel $pesel,
        private string $gender,
        private bool $isActive = false
    ) {
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function id(): UuidV4
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    /**
     * @return string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }

    public function setLaboratoryId(UuidV4 $id): void
    {
        $this->laboratoryId = $id;
    }
}
