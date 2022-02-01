<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

class LaboratoryDetails
{
    public function __construct(
        private UuidV4 $labWorkerId,
        private string $labWorkerFullName,
        private UuidV4 $laboratoryId,
        private string $labName
    ) {
    }

    public static function fromArray(array $result): LaboratoryDetails
    {
        return new LaboratoryDetails(
            UuidV4::fromString($result['lab_worker_id']),
            sprintf('%s %s', $result['lab_worker_first_name'], $result['lab_worker_last_name']),
            UuidV4::fromString($result['lab_id']),
            $result['lab_name']
        );
    }

    public function labWorkerId(): UuidV4
    {
        return $this->labWorkerId;
    }

    public function labWorkerFullName(): string
    {
        return $this->labWorkerFullName;
    }

    public function laboratoryId(): UuidV4
    {
        return $this->laboratoryId;
    }

    public function labName(): string
    {
        return $this->labName;
    }
}
