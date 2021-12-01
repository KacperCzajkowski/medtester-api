<?php

declare(strict_types=1);

namespace App\Users\Domain;

use Symfony\Component\Uid\UuidV4;

class UserActivation
{
    public function __construct(
        private UuidV4 $id,
        private UuidV4 $userId,
        private \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $usedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null
    ) {
    }

    public function id(): UuidV4
    {
        return $this->id;
    }

    public function cancelActivationToken(\DateTimeImmutable $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    public function userId(): UuidV4
    {
        return $this->userId;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setUsedAt(\DateTimeImmutable $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    public function usedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }
}
