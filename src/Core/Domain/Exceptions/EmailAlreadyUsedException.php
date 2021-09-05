<?php

declare(strict_types=1);

namespace App\Core\Domain\Exceptions;

use App\Core\Domain\Email;

class EmailAlreadyUsedException extends \Exception
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf('Email %s is already used', $email->value()));
    }
}
