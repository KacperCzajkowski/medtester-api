<?php

declare(strict_types=1);

namespace App\Users\Domain;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

interface UserRepository
{
    public function findUserByEmail(Email $email): ?User;

    public function addUser(User $user): void;

    public function findUserById(UuidV4 $id): ?User;
}
