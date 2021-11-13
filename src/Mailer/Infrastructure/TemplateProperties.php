<?php

declare(strict_types=1);

namespace App\Mailer\Infrastructure;

class TemplateProperties
{
    /**
     * @param array<string, string> $params
     */
    public function __construct(
        private string $path,
        private array $params
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return array<string, string>
     */
    public function params(): array
    {
        return $this->params;
    }
}
