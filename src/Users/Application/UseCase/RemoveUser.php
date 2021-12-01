<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Users\Application\Exception\ActionNotAllowedException;
use App\Users\Domain\UserActivationRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveUser implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserActivationRepository $userActivationRepository,
        private Clock $clock
    ) {
    }

    public function __invoke(RemoveUser\Command $command): void
    {
        $userToRemove = $this->userRepository->findUserById($command->userId());

        if (!$userToRemove) {
            throw UserNotFoundException::byId($command->userId());
        }

        $updatedById = $command->requestedBy();
        if (!$command->userId()->equals($command->requestedBy())) {
            $requestedBy = $this->userRepository->findUserById($command->requestedBy());

            if (!$requestedBy) {
                throw UserNotFoundException::byId($command->requestedBy());
            }

            if (!in_array('ROLE_SYSTEM_ADMIN', $requestedBy->roles(), true)) {
                throw ActionNotAllowedException::byNotHavingNeededPermissions($requestedBy->id());
            }
        }

        $userToRemove->markAsRemoved($updatedById, $this->clock);

        $activation = $this->userActivationRepository->findActiveActivationByUserId($userToRemove->id());

        if ($activation) {
            $activation->cancelActivationToken($this->clock->currentDateTime());
        }
    }
}
