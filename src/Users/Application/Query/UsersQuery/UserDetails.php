<?php

declare(strict_types=1);

namespace App\Users\Application\Query\UsersQuery;

class UserDetails implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
        private string $pesel,
        private string $gender,
        private ?LaboratoryDetails $labDetails,
    ) {
    }

    public static function fromArray(string $id, array $result): UserDetails
    {
        return new UserDetails(
            $id,
            $result['first_name'],
            $result['last_name'],
            $result['email'],
            new \DateTimeImmutable($result['created_at']),
            new \DateTimeImmutable($result['updated_at']),
            $result['pesel'],
            $result['gender'],
            LaboratoryDetails::fromArray($result)
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'createdAt' => $this->createdAt->format('d-m-y'),
            'updatedAt' => $this->updatedAt->format('d-m-y'),
            'pesel' => $this->pesel,
            'gender' => $this->gender,
            'labDetails' => $this->labDetails
        ];
    }
}
