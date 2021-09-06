<?php

declare(strict_types=1);

namespace App\Core\Domain\DoctrineTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Pesel\Pesel;

class PeselType extends Type
{
    const TYPE_NAME = 'pesel';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'CHAR(11)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Pesel
    {
        return $value ? new Pesel($value) : null;
    }

    /**
     * @param Pesel|null $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value?->getNumber();
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }
}
