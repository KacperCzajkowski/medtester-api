<?php

namespace App\Users\Application\UseCase;

use App\Core\Domain\Email;
use App\Users\Domain\SystemAdmin;

interface SystemAdminRepository
{
    public function findUserByEmail(Email $email): ?SystemAdmin;

    public function addAdmin(SystemAdmin $admin): void;
}
