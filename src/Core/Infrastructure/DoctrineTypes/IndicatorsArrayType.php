<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\DoctrineTypes;

use App\MedicalTests\Domain\Indicator;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class IndicatorsArrayType extends Type
{
    const TYPE_NAME = 'indicators_array';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'JSONB';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        if (!$value) {
            return [];
        }
        $result = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        $convertedArray = [];

        foreach ($result as $singleTest) {
            $convertedArray[] = Indicator::fromArray($singleTest);
        }

        return $convertedArray;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
