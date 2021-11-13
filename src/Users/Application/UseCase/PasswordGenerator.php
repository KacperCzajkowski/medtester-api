<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

interface PasswordGenerator
{
    public function getNew(): string;
}
