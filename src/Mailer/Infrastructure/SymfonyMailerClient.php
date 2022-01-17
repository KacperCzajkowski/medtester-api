<?php

declare(strict_types=1);

namespace App\Mailer\Infrastructure;

use App\Core\Domain\Email;
use App\Mailer\Application\MailingClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SymfonyMailerClient implements MailingClient
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendTemplatedEmail(TemplatedEmailSchema $schema): void
    {
        $email = (new TemplatedEmail())
            ->from($schema->from()->value())
            ->to(...array_map(static fn (Email $email) => $email->value(), $schema->to()))
            ->subject($schema->subject())
            ->htmlTemplate($schema->properties()->path())
            ->context($schema->properties()->params());

        foreach ($schema->attachmentsUrl() as $url) {
            $email = $email->attachFromPath($url);
        }

        $this->mailer->send($email);
    }

    public function sendEmailWithAttachmentStream(StreamedAttachmentEmailSchema $schema): void
    {
        $email = (new TemplatedEmail())
            ->from($schema->from()->value())
            ->to(...array_map(static fn (Email $email) => $email->value(), $schema->to()))
            ->subject($schema->subject())
            ->htmlTemplate($schema->properties()->path())
            ->context($schema->properties()->params());

        foreach ($schema->attachmentsStreams() as $stream) {
            $email = $email->attach($stream['source'], $stream['name']);
        }

        $this->mailer->send($email);
    }
}
