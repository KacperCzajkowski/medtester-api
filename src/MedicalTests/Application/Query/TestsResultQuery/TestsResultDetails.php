<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class TestsResultDetails implements \JsonSerializable
{
    /**
     * @param SingleTest[] $results
     */
    public function __construct(
        private string $id,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
        private LaboratoryWorkerDetails $labWorkerDetails,
        private array $results
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt->format('d-m-y'),
            'updatedAt' => $this->updatedAt->format('d-m-y'),
            'labWorkerDetails' => $this->labWorkerDetails,
            'results' => $this->results,
        ];
    }

    public static function fromArray(array $result): TestsResultDetails
    {
        $tests = json_decode($result['results'], true, 512, JSON_THROW_ON_ERROR);

        return new TestsResultDetails(
            $result['id'],
            new \DateTimeImmutable($result['created_at']),
            new \DateTimeImmutable($result['updated_at']),
            LaboratoryWorkerDetails::fromArray($result),
            array_map(static fn (array $singleTest): SingleTest => SingleTest::fromArray($singleTest), $tests)
        );
    }
}
