<?php

declare(strict_types=1);

namespace App\MedicalTests\Domain;

class Indicator implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private float $result,
        private string $unit,
        private ReferenceRange $referenceRange
    ) {
    }
    /**
     * @return ReferenceRange
     */
    public function referenceRange(): ReferenceRange
    {
        return $this->referenceRange;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'result' => $this->result,
            'unit' => $this->unit,
            'referenceRange' => $this->referenceRange,
        ];
    }

    public static function fromArray(array $result): self
    {
        return new Indicator(
            $result['name'],
            $result['result'],
            $result['unit'],
            ReferenceRange::fromArray($result['referenceRange'])
        );
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function result(): float
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function unit(): string
    {
        return $this->unit;
    }
}
