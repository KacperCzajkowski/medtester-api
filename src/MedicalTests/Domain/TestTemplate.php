<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

use Symfony\Component\Uid\UuidV4;

class TestTemplate implements \JsonSerializable
{
    /**
     * @param Indicator[] $hardcodedIndicators
     */
    public function __construct(
        private UuidV4 $id,
        private string $name,
        private string $icdCode,
        private \DateTimeImmutable $createdAt,
        private array $hardcodedIndicators
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'name' => $this->name,
            'icdCode' => $this->icdCode,
            'createdAt' => $this->createdAt,
            'hardcodedIndicators' => $this->hardcodedIndicators,
        ];
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icdCode(): string
    {
        return $this->icdCode;
    }

    public function hardcodedIndicators(): array
    {
        return $this->hardcodedIndicators;
    }
}
