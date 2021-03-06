<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use App\Core\Domain\Clock;
use App\MedicalTests\Application\Usecase\SaveTestsResult;
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

    public function updateFromCommandAndClock(SaveTestsResult\Command $command, Clock $clock): void
    {
        $this->status = $command->status();
        $this->updatedAt = $clock->currentDateTime();
        $this->results = $command->results();
    }

    /**
     * @return SingleTest[]
     */
    public function results(): array
    {
        return $this->results;
    }

    public function userId(): UuidV4
    {
        return $this->userId;
    }

    public function laboratoryWorkerId(): UuidV4
    {
        return $this->laboratoryWorkerId;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isDone(): bool
    {
        return 'done' === $this->status;
    }

    public function id(): UuidV4
    {
        return $this->id;
    }

    public function addNewSingleTest(SingleTest $fromTemplate): void
    {
        $this->results[] = $fromTemplate;
    }
}
