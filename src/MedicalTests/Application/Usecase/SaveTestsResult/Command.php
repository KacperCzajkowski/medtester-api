<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase\SaveTestsResult;

use Symfony\Component\Uid\UuidV4;

class Command
{
    /**
     * @param array{
     *  name: string,
     *  icdCode: string,
     *  indicators: array{
     *   name: string,
     *   result: float,
     *   unit: string,
     *   referenceRange: array{
     *    min: float | null,
     *    max: float | null
     *   }
     *  }[]
     * }[] $results
     */
    public function __construct(
        private UuidV4 $laboratoryWorkerId,
        private string $status,
        private array $results
    ) {
    }

    public function laboratoryWorkerId(): UuidV4
    {
        return $this->laboratoryWorkerId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function results(): array
    {
        return $this->results;
    }
}
