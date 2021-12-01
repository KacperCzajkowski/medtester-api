<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Core\Domain\Email;
use App\Mailer\Infrastructure\TemplatedEmailSchema;
use App\Mailer\Infrastructure\TemplateProperties;

class DummyEmailSender implements EmailSender
{
    public function __construct(
        private MailingClient $client,
        private string $senderEmail,
        private string $frontendUrl
    ) {
    }

    public function sendEmailWithNewPassword(Email $emailTo, string $firstName, string $newPassword, string $tokenId): void
    {
        $schema = new TemplatedEmailSchema(
            $from = new Email($this->senderEmail),
            $to = [$emailTo],
            $subject = 'Witamy w platformie',
            new TemplateProperties('createAccount.html.twig', [
                'userFirstName' => $firstName,
                'newPassword' => $newPassword,
                'frontendUrl' => $this->frontendUrl,
                'tokenId' => $tokenId,
            ])
        );

        $this->client->sendTemplatedEmail($schema);
    }
}
