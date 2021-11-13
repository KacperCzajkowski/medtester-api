<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Core\Domain\Email;

class TemplatedEmailSchema
{
    /**
     * @param Email[] $to
     * @param string[] $attachmentsUrl
     */
    public function __construct(
        private Email $from,
        private array $to,
        private string $subject,
        private TemplateProperties $properties,
        private array $attachmentsUrl = []
    ) {
    }

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

    public function subject(): string
    {
        return $this->subject;
    }

    public function properties(): TemplateProperties
    {
        return $this->properties;
    }

    /**
     * @return string[]
     */
    public function attachmentsUrl(): array
    {
        return $this->attachmentsUrl;
    }
}
