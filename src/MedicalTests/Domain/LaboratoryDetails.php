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
    ) {}

    public static function fromArray(array $result): LaboratoryDetails
    {
        return new LaboratoryDetails(
            UuidV4::fromString($result['lab_worker_id']),
            sprintf('%s %s', $result['lab_worker_first_name'], $result['lab_worker_last_name']),
            UuidV4::fromString($result['lab_id']),
            $result['lab_name']
        );

    }

    /**
     * @return UuidV4
     */
    public function labWorkerId(): UuidV4
    {
        return $this->labWorkerId;
    }

    /**
     * @return string
     */
    public function labWorkerFullName(): string
    {
        return $this->labWorkerFullName;
    }

    /**
     * @return UuidV4
     */
    public function laboratoryId(): UuidV4
    {
        return $this->laboratoryId;
    }

    /**
     * @return string
     */
    public function labName(): string
    {
        return $this->labName;
    }
}