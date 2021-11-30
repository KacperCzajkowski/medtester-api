<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangePassword implements MessageHandlerInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(ChangePassword\Command $command): void
    {
        $user = $this->userRepository->findUserById($command->userId());

        if (!$user) {
            throw UserNotFoundException::byId($command->userId());
        }

        $user->setPassword($command->hashedNewPassword());
    }
}
