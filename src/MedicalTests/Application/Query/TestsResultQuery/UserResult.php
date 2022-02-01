<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class UserResult implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $labWorkerFullName,
        private string $labName,
        private \DateTimeImmutable $createdAt,
        private int $testsCount
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'labWorkerFullName' => $this->labWorkerFullName,
            'labName' => $this->labName,
            'createdAt' => $this->createdAt->format('d-m-y'),
            'testsCount' => $this->testsCount,
        ];
    }

    public static function fromArray(array $result): UserResult
    {
        return new UserResult(
            $result['id'],
            sprintf(
                '%s %s',
                $result['laboratory_worker_last_name'],
                $result['laboratory_worker_first_name']
            ),
            $result['laboratory_name'],
            new \DateTimeImmutable($result['created_at']),
            $result['tests_count']
        );
    }
}
