<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;

interface UserRepository
{
    public function findUserByEmail(Email $email): ?User;

    public function addUser(User $user): void;
}
