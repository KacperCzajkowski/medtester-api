<?php

declare(strict_types=1);

namespace App\Mailer\Application;

use App\Core\Domain\Email;
use App\Mailer\Infrastructure\StreamedAttachmentEmailSchema;
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

    public function sendInvitationEmail(Email $emailTo, string $firstName, string $newPassword, string $tokenId): void
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

    public function sentEmailWithNewPassword(Email $emailTo, string $firstName, string $newPassword): void
    {
        $schema = new TemplatedEmailSchema(
            $from = new Email($this->senderEmail),
            $to = [$emailTo],
            $subject = 'Zmiana hasła',
            new TemplateProperties('newPassword.html.twig', [
                'userFirstName' => $firstName,
                'frontendUrl' => $this->frontendUrl,
                'newPassword' => $newPassword,
            ])
        );

        $this->client->sendTemplatedEmail($schema);
    }

    public function sendEmailWithTestsResultAsPdf(UuidV4 $id): void
    {
        $schema = new StreamedAttachmentEmailSchema(
            $from = new Email($this->senderEmail),
            $to = [$emailTo],
            $subject = 'Zmiana hasła',
            new TemplateProperties('newPassword.html.twig', [
                'userFirstName' => $firstName,
                'frontendUrl' => $this->frontendUrl,
                'newPassword' => $newPassword,
            ])
        );

        $this->client->sendEmailWithAttachmentStream($schema);
    }
}
