<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\ResetPassword;

use App\Core\Domain\Email;

class Command
{
    public function __construct(private string $email)
    {
    }

    public function email(): Email
    {
        return new Email($this->email);
    }
}
