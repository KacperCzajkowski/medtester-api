<?php

namespace App\MedicalTests\Application\Query\TestTemplateQuery;

class TestTemplateDetails implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private string $icdCode
    )
    {
    }

    public static function fromArray(array $result): TestTemplateDetails
    {
        return new TestTemplateDetails(
            $result['id'],
            $result['name'],
            $result['icd_code']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icdCode' => $this->icdCode
        ];
    }
}