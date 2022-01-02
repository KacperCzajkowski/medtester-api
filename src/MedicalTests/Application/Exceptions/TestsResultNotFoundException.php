<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Exceptions;

use Symfony\Component\Uid\UuidV4;

class TestsResultNotFoundException extends \LogicException
{
    public static function byLabWorkerId(UuidV4 $id): TestsResultNotFoundException
    {
        return new TestsResultNotFoundException(
            sprintf('Tests result for laboratory worker with id %s was not found', $id->toRfc4122())
        );
    }
}
