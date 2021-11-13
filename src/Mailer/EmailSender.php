<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Core\Domain\Email;

interface EmailSender
{
    public function sendEmailWithNewEmail(Email $emailTo): void;
}
