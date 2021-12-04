<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

class ReferenceRange implements \JsonSerializable
{
    public function __construct(
        private ?int $min = null,
        private ?int $max = null
    ) {
    }

    public function min(): ?int
    {
        return $this->min;
    }

    public function max(): ?int
    {
        return $this->max;
    }

    public function jsonSerialize(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
