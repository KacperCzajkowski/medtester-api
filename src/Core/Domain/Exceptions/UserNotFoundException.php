<?php

declare(strict_types=1);

namespace App\Core\Domain\Exceptions;

use Symfony\Component\Uid\UuidV4;

class UserNotFoundException extends \Exception
{
    public function __construct(UuidV4 $uuid)
    {
        parent::__construct(sprintf('User with id %s was not found', $uuid->toRfc4122()));
    }
}
