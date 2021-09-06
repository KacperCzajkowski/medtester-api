<?php

declare(strict_types=1);

namespace App\Core\Domain\Clock;

use App\Core\Domain\Clock;

class SystemClock implements Clock
{
    public function currentDateTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
