<?php

declare(strict_types=1);

namespace App\Users\Infrastructure;

use App\Core\Domain\Email;
use App\Users\Domain\User;
use App\Users\Domain\UserRepository as UserRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private ManagerRegistry $manager)
    {
    }

    public function findUserByEmail(Email $email): ?User
    {
        return $this->manager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
    }

    public function addUser(User $user): void
    {
        /**
         * @var ObjectManager $manager
         */
        $manager = $this->manager->getManagerForClass(User::class);

        $manager->persist($user);
    }
}