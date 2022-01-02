<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Query\TestsResultQuery;

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

    public static function fromArray(array $array): SingleTest
    {
        return new self(
            $array['name'],
            $array['icdCode'],
            array_map(static fn (array $indicator): Indicator => Indicator::fromArray($indicator), $array['indicators'])
        );
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
