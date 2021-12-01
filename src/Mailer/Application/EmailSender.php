<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Core\Domain\Email;
use Symfony\Component\Uid\UuidV4;

interface EmailSender
{
    public function sendEmailWithNewPassword(Email $emailTo, string $firstName, string $newPassword, string $tokenId): void;

    public function sendEmailWithActivationLink(Email $emailTo, UuidV4 $activationId, string $firstName);
}
