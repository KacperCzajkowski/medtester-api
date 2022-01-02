<?php

declare(strict_types=1);

namespace App\Users\Application\Query\UsersQuery;

class BasicUserInfo implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private string $lastName,
        private string $pesel
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'fullName' => sprintf('%s %s', $this->name, $this->lastName),
            'pesel' => $this->pesel,
        ];
    }

    public static function fromArray(array $array): self
    {
        return new BasicUserInfo(
            $array['first_name'],
            $array['last_name'],
            $array['pesel']
        );
    }
}
