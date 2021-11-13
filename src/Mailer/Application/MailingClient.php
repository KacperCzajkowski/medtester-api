<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Mailer\Infrastructure\TemplatedEmailSchema;

interface MailingClient
{
    public function sendTemplatedEmail(TemplatedEmailSchema $schema): void;
}
