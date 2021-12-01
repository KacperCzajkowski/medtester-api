<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Core\Domain\Email;

interface EmailSender
{
    public function sendEmailWithNewPassword(Email $emailTo, string $firstName, string $newPassword, string $tokenId): void;
}
