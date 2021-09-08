<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Core\Domain\Email;
use App\Users\Application\UseCase\SystemAdminRepository as SystemAdminRepositoryInterface;
use App\Users\Domain\SystemAdmin;
use Doctrine\Persistence\ManagerRegistry;

class SystemAdminRepository implements SystemAdminRepositoryInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
    ) {
    }

    public function findUserByEmail(Email $email)
    {
        return $this->managerRegistry
            ->getRepository(SystemAdmin::class)
            ->findOneBy([
                'email' => $email
            ]);
    }
}
