<?php

declare(strict_types=1);

namespace App\Users\Application\Exception;

use Symfony\Component\Uid\UuidV4;

class IllegalArgumentException extends \Exception
{
    public static function byInvalidDataToCreateLaboratoryWorker(UuidV4 $createdBy): IllegalArgumentException
    {
        return new IllegalArgumentException(
            sprintf('Provided data by %s to create lab worker are invalid', $createdBy->toRfc4122()),
            $code = 400
        );
    }
}
