<?php

declare(strict_types=1);

namespace App\Users\Application\Query\UsersQuery;

class LaboratoryDetails implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private \DateTimeImmutable $createdAt
    )
    {
    }

    public static function fromArray(array $result): ?LaboratoryDetails
    {
        if (self::checkIfLabDetailsCanBeCreated($result)) {
            return null;
        }

        return new LaboratoryDetails(
            $result['laboratory_id'],
            $result['laboratory_name'],
            new \DateTimeImmutable($result['laboratory_created_at'])
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'laboratoryId' => $this->id,
            'laboratoryName' => $this->name,
            'laboratoryCreatedAt' => $this->createdAt->format('d-m-y')
        ];
    }

    private static function checkIfLabDetailsCanBeCreated(array $result): bool
    {
        return is_null($result['laboratory_id']) ||
            is_null($result['laboratory_name']) ||
            is_null($result['laboratory_created_at']);
    }
}