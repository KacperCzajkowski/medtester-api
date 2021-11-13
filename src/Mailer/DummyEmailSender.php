<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Core\Domain\Email;

class DummyEmailSender implements EmailSender
{
    public function __construct(
        private MailingClient $client,
        private string $senderEmail
    ) {
    }

    public function sendEmailWithNewEmail(Email $emailTo): void
    {
        $schema = new TemplatedEmailSchema(
            $from = new Email($this->senderEmail),
            $to = [new Email('test@test.pl')],
            $subject = 'Witamy w platformie',
            new TemplateProperties('url', [])
        );

        $this->client->sendTemplatedEmail($schema);
    }
}
