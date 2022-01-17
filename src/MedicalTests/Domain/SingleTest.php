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

    public static function fromArray(array $array): self
    {
        return new SingleTest(
            $array['name'],
            $array['icdCode'],
            array_map(static fn (array $tmp): Indicator => Indicator::fromArray($tmp), $array['indicators'])
        );
    }

    public function icdCode(): string
    {
        return $this->icdCode;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Indicator[]
     */
    public function indicators(): array
    {
        return $this->indicators;
    }
}
