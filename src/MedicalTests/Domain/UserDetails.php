<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use App\Core\Domain\Email;
use Pesel\Pesel;

class UserDetails
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private Email $email,
        private Pesel $pesel,
        private string $gender
    ) {}

    public static function fromArray(array $result): UserDetails
    {
        return new UserDetails(
            $result['user_first_name'],
            $result['user_last_name'],
            new Email($result['user_email']),
            new Pesel($result['user_pesel']),
            $result['user_gender']
        );
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * @return Pesel
     */
    public function pesel(): Pesel
    {
        return $this->pesel;
    }

    /**
     * @return string
     */
    public function gender(): string
    {
        return $this->gender;
    }
}