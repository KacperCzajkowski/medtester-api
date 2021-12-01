<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Mailer\Application\EmailSender;
use App\Security\User;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPassword implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordGenerator $passwordGenerator,
        private EmailSender $emailSender,
        private Clock $clock,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function __invoke(ResetPassword\Command $command): void
    {
        $user = $this->userRepository->findUserByEmail($command->email());

        if (!$user) {
            throw UserNotFoundException::byEmail($command->email());
        }

        $newPassword = $this->passwordGenerator->getNew();

        $user->setPassword(
            $this->hasher->hashPassword(new User($user), $newPassword),
            $user->id(),
            $this->clock
        );

        $this->emailSender->sentEmailWithNewPassword($user->email(), $user->firstName(), $newPassword);
    }
}
