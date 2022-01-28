<?php

namespace App\MedicalTests\Application\Usecase\AddTemplateToResult;

use Symfony\Component\Uid\UuidV4;

class Command
{
    public function __construct(
        private string $labWorkerId,
        private string $templateId
    ) {}

    public function labWorkerId(): UuidV4
    {
        return UuidV4::fromString($this->labWorkerId);
    }

    public function templateId(): UuidV4
    {
        return UuidV4::fromString($this->templateId);
    }
}