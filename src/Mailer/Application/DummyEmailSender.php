<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Core\Domain\Email;
use App\Mailer\Infrastructure\TemplatedEmailSchema;
use App\Mailer\Infrastructure\TemplateProperties;
use Symfony\Component\Uid\UuidV4;

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

    public function sendEmailWithActivationLink(Email $emailTo, UuidV4 $activationId, string $firstName): void
    {
        $schema = new TemplatedEmailSchema(
            $from = new Email($this->senderEmail),
            $to = [$emailTo],
            $subject = 'Aktywacja konta',
            new TemplateProperties('activateAccount.html.twig', [
                'userFirstName' => $firstName,
                'frontendUrl' => $this->frontendUrl,
                'tokenId' => $activationId->toRfc4122(),
            ])
        );

        $this->client->sendTemplatedEmail($schema);
    }
}
