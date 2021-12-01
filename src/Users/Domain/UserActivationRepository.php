<?php

declare(strict_types=1);

namespace App\Users\Domain;

use Symfony\Component\Uid\UuidV4;

interface UserActivationRepository
{
    public function findActivationById(UuidV4 $id): ?UserActivation;

    public function addActivation(UserActivation $activation): void;

    public function findActiveActivationByUserId(UuidV4 $userId): ?UserActivation;
}
