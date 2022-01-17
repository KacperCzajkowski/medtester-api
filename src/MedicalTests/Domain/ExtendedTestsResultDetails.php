<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

class ExtendedTestsResultDetails
{
    /**
     * @param SingleTest[] $results
     */
    public function __construct(
        private UuidV4 $id,
        private \DateTimeImmutable $createdAt,
        private LaboratoryDetails $laboratoryDetails,
        private UserDetails $userDetails,
        private array $results
    ) {}

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id->toRfc4122();
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('h:m d-m-Y');
    }

    /**
     * @return LaboratoryDetails
     */
    public function laboratoryDetails(): LaboratoryDetails
    {
        return $this->laboratoryDetails;
    }

    /**
     * @return UserDetails
     */
    public function userDetails(): UserDetails
    {
        return $this->userDetails;
    }

    /**
     * @return SingleTest[]
     */
    public function results(): array
    {
        return $this->results;
    }
}