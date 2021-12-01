<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\Mailer\Application\EmailSender;
use App\Users\Domain\UserActivation;
use App\Users\Domain\UserActivationRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\UuidV4;

class ChangeEmail implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserActivationRepository $userActivationRepository,
        private Clock $clock,
        private EmailSender $emailSender
    ) {
    }

    public function __invoke(ChangeEmail\Command $command): void
    {
        $alreadyUsed = $this->userRepository->findUserByEmail($command->newEmail());

        if ($alreadyUsed) {
            throw new EmailAlreadyUsedException($command->newEmail());
        }

        $user = $this->userRepository->findUserById($command->userId());

        if (!$user) {
            throw UserNotFoundException::byId($command->userId());
        }

        $user->updateUserEmail($command->newEmail(), $command->userId(), $this->clock);

        $existingActivation = $this->userActivationRepository->findActiveActivationByUserId($command->userId());

        if ($existingActivation) {
            $existingActivation->cancelActivationToken($this->clock->currentDateTime());
        }

        $newActivation = new UserActivation(
            $activationId = UuidV4::v4(),
            $command->userId(),
            $this->clock->currentDateTime()
        );

        $this->userActivationRepository->addActivation($newActivation);

        $this->emailSender->sendEmailWithActivationLink($command->newEmail(), $activationId, $user->firstName());
    }
}
