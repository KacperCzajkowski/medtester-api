<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

class SingleTest implements \JsonSerializable
{
    /**
     * @param Indicator[] $indicators
     */
    public function __construct(
        private string $name,
        private string $icdCode,
        private array $indicators
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'icdCode' => $this->icdCode,
            'indicators' => $this->indicators,
        ];
    }
}
