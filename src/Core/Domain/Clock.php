<?php

declare(strict_types=1);

namespace App\Core\Domain;

interface Clock
{
    public function currentDateTime(): \DateTimeImmutable;
}
