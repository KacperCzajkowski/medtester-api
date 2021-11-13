<?php

declare(strict_types=1);

namespace App\Mailer;

interface MailingClient
{
    public function sendTemplatedEmail(TemplatedEmailSchema $schema): void;
}
