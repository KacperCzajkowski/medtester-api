<?php

namespace App\Users\Infrastructure;

use App\Users\Application\UseCase\PasswordGenerator;

class SimplePasswordGenerator implements PasswordGenerator
{
    private const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    private const PASSWORD_LENGTH = 8;

    public function getNew(): string
    {
        $pass = [];
        $alphaLength = strlen(self::ALPHABET) - 1;
        for ($i = 0; $i < self::PASSWORD_LENGTH; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = self::ALPHABET[$n];
        }
        return implode($pass);
    }
}
