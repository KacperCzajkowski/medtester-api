<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class LaboratoryWorkerDetails implements \JsonSerializable
{
    public function __construct(
        private string $labWorkerId,
        private string $firstName,
        private string $lastName,
        private string $labId,
        private string $labName
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'labWorkerId' => $this->labWorkerId,
            'fullName' => sprintf('%s %s',$this->firstName, $this->lastName),
            'labName' => $this->labName,
            'labId' => $this->labId
        ];
    }

    public static function fromArray(array $result): LaboratoryWorkerDetails
    {
        return new LaboratoryWorkerDetails(
            $result['lab_worker_id'],
            $result['lab_worker_first_name'],
            $result['lab_worker_last_name'],
            $result['lab_id'],
            $result['lab_name']
        );
    }
}