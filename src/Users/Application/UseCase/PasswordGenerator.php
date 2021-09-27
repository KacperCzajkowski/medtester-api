<?php

namespace App\Users\Application\UseCase;

interface PasswordGenerator
{
    public function getNew(): string;
}
