<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class PatientDetails implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $email,
        private string $firstName,
        private string $lastName,
        private string $pesel,
        private string $gender,
    ) {
    }

    public static function fromArray(array $result): self
    {
        return new PatientDetails(
            $result['user_id'],
            $result['email'],
            $result['first_name'],
            $result['last_name'],
            $result['pesel'],
            $result['gender'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'pesel' => $this->pesel,
            'gender' => $this->gender,
        ];
    }
}
