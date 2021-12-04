<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

class TestsResult implements \JsonSerializable
{
    /**
     * @param SingleTest[] $results
     */
    public function __construct(
        private Uuidv4 $id,
        private UuidV4 $userId,
        private UuidV4 $laboratoryWorkerId,
        private string $status,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
        private array $results
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'laboratoryWorkerId' => $this->laboratoryWorkerId,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'results' => $this->results,
        ];
    }
}
