<?php

declare(strict_types=1);

namespace App\Mailer\Infrastructure;

use App\Core\Domain\Email;

class StreamedAttachmentEmailSchema
{
    /**
     * @param Email[] $to
     * @param array{name: string, source: string}[] $attachmentsStreams
     */
    public function __construct(
        private Email $from,
        private array $to,
        private string $subject,
        private TemplateProperties $properties,
        private array $attachmentsStreams = []
    ) {
    }

    /**
     * @return Email
     */
    public function from(): Email
    {
        return $this->from;
    }

    /**
     * @return Email[]
     */
    public function to(): array
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return TemplateProperties
     */
    public function properties(): TemplateProperties
    {
        return $this->properties;
    }

    public function attachmentsStreams(): array
    {
        return $this->attachmentsStreams;
    }
}