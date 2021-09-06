<?php

declare(strict_types=1);

namespace App\Core\Domain\Clock;

use App\Core\Domain\Clock;

class TestClock implements Clock
{
    private ?\DateTimeImmutable $date = null;

    public function currentDateTime(): \DateTimeImmutable
    {
        return $this->date ?? new \DateTimeImmutable();
    }

    public function setCurrentDateTime(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }
}
