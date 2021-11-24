<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\ChangePassword;

use App\Core\Domain\Email;
use Pesel\Pesel;
use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private UuidV4 $userId,
        private string $hashedNewPassword
    ) {}

    /**
     * @return UuidV4
     */
    public function userId(): UuidV4
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function hashedNewPassword(): string
    {
        return $this->hashedNewPassword;
    }
}
