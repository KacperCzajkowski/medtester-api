<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Clock;
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
    private ?\DateTimeImmutable $removedAt = null;

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

    public static function patientFromArray(array $result): self
    {
        return new User(
            UuidV4::fromString($result['id']),
            $result['first_name'],
            $result['last_name'],
            new Email($result['email']),
            [self::ROLES['ROLE_PATIENT']],
            new \DateTimeImmutable($result['created_at']),
            UuidV4::fromString($result['created_by']),
            new \DateTimeImmutable($result['updated_at']),
            UuidV4::fromString($result['updated_by']),
            new Pesel($result['pesel']),
            $result['gender'],
            (bool) $result['is_active']
        );
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setPassword(string $password, UuidV4 $updatedBy, Clock $clock): void
    {
        $this->password = $password;
        $this->updateAuditInfo($updatedBy, $clock);
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

    public function setLaboratoryId(UuidV4 $id, UuidV4 $updatedBy, Clock $clock): void
    {
        $this->laboratoryId = $id;
        $this->updateAuditInfo($updatedBy, $clock);
    }

    public function activateUser(UuidV4 $updatedBy, Clock $clock): void
    {
        $this->isActive = true;
        $this->updateAuditInfo($updatedBy, $clock);
    }

    public function updateUserEmail(Email $newEmail, UuidV4 $userId, Clock $clock): void
    {
        $this->email = $newEmail;
        $this->isActive = false;
        $this->updateAuditInfo($userId, $clock);
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function markAsRemoved(UuidV4 $updatedBy, Clock $clock): void
    {
        $this->removedAt = $clock->currentDateTime();
        $this->updateAuditInfo($updatedBy, $clock);
    }

    public function removedAt(): ?\DateTimeImmutable
    {
        return $this->removedAt;
    }

    public function laboratoryId(): ?UuidV4
    {
        return $this->laboratoryId;
    }

    private function updateAuditInfo(UuidV4 $updatedBy, Clock $clock): void
    {
        $this->updatedBy = $updatedBy;
        $this->updatedAt = $clock->currentDateTime();
    }
}
