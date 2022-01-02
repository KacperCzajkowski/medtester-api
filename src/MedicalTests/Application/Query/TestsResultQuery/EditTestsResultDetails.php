<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class EditTestsResultDetails implements \JsonSerializable
{
    /**
     * @param SingleTest[] $results
     */
    public function __construct(
        private PatientDetails $patientDetails,
        private array $results
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'patientDetails' => $this->patientDetails,
            'results' => $this->results,
        ];
    }

    public static function fromArray(array $array): self
    {
        $decodedResults = json_decode($array['results'], true, 512, JSON_THROW_ON_ERROR);

        return new self(
            PatientDetails::fromArray($array),
            array_map(static fn (array $result): SingleTest => SingleTest::fromArray($result), $decodedResults)
        );
    }
}
