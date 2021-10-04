<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Security\User;
use App\Users\Domain\SystemAdmin;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateSystemAdmin implements MessageHandlerInterface
{
    public function __construct(
        private SystemAdminRepository $systemAdminRepository,
        private Clock $clock,
        private PasswordGenerator $passwordGenerator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(CreateSystemAdmin\Command $command): void
    {
        $exists = $this->systemAdminRepository->findUserByEmail($command->email());

        if ($exists) {
            throw new EmailAlreadyUsedException($command->email());
        }

        $admin = new SystemAdmin(
            $command->id(),
            $command->firstName(),
            $command->lastName(),
            $command->email(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
        );

        $password = $this->passwordGenerator->getNew();

        $admin->setPassword(
            $this->passwordHasher->hashPassword(new User($admin), $password)
        );

        $this->systemAdminRepository->addAdmin($admin);
    }
}
