<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

class ReferenceRange implements \JsonSerializable
{
    public function __construct(
        private ?float $min = null,
        private ?float $max = null
    ) {
    }

    public function min(): ?float
    {
        return $this->min;
    }

    public function max(): ?float
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

    public static function fromArray(array $range): self
    {
        return new ReferenceRange($range['min'], $range['max']);
    }
}
