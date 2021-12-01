<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Users\Application\Exception\ActivationNotFoundException;
use App\Users\Domain\UserActivationRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ActivateUser implements MessageHandlerInterface
{
    public function __construct(
        private UserActivationRepository $userActivationRepository,
        private UserRepository $userRepository,
        private Clock $clock
    ) {
    }

    public function __invoke(ActivateUser\Command $command): void
    {
        $activation = $this->userActivationRepository->findActivationById($command->tokenId());

        if (!$activation) {
            throw ActivationNotFoundException::byId($command->tokenId());
        }

        $user = $this->userRepository->findUserById($activation->userId());

        if (!$user) {
            throw UserNotFoundException::byId($activation->userId());
        }

        $activation->setUsedAt($this->clock->currentDateTime());
        $user->activateUser($user->id(), $this->clock);
    }
}
