<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use App\Core\Domain\Clock;
use App\MedicalTests\Application\SaveTestsResult;
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

    public static function fromCommandAndClock(SaveTestsResult\Command $command, Clock $clock): self
    {
        return new TestsResult(
            $command->testId(),
            $command->userId(),
            $command->laboratoryWorkerId(),
            $command->status(),
            $clock->currentDateTime(),
            $clock->currentDateTime(),
            array_map(static fn (array $array): SingleTest => SingleTest::fromArray($array), $command->results())
        );
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
}
