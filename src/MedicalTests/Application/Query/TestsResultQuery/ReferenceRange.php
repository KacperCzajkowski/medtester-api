<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

class ReferenceRange implements \JsonSerializable
{
    public function __construct(
        private ?float $min,
        private ?float $max
    ) {
    }

    public static function fromArray(array $array): ReferenceRange
    {
        return new self(
            (float) $array['min'],
            (float) $array['max']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
