<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Laboratory\Domain\LaboratoryRepository;
use App\Mailer\Application\EmailSender;
use App\Security\User;
use App\Users\Application\Exception\IllegalArgumentException;
use App\Users\Domain\User as DomainUser;
use App\Users\Domain\UserActivation;
use App\Users\Domain\UserActivationRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUser implements MessageHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private Clock $clock,
        private PasswordGenerator $passwordGenerator,
        private UserPasswordHasherInterface $passwordHasher,
        private EmailSender $emailSender,
        private UserActivationRepository $userActivationRepository,
        private LaboratoryRepository $laboratoryRepository
    ) {
    }

    public function __invoke(CreateUser\Command $command): void
    {
        $exists = $this->userRepository->findUserByEmail($command->email());

        if ($exists) {
            throw new EmailAlreadyUsedException($command->email());
        }

        $newUser = new DomainUser(
            $command->id(),
            $command->firstName(),
            $command->lastName(),
            $command->email(),
            $command->roles(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $this->clock->currentDateTime(),
            $command->createdBy(),
            $command->pesel(),
            $command->gender()
        );

        $labId = $command->laboratoryId();

        if (in_array('ROLE_LABORATORY_WORKER', $command->roles(), true)) {
            if ($labId) {
                $lab = $this->laboratoryRepository->findLaboratoryById($labId);

                if (!$lab) {
                    throw IllegalArgumentException::byInvalidDataToCreateLaboratoryWorker($command->createdBy());
                }

                $newUser->setLaboratoryId($labId, $command->createdBy(), $this->clock);
            } else {
                throw IllegalArgumentException::byInvalidDataToCreateLaboratoryWorker($command->createdBy());
            }
        }

        $password = $this->passwordGenerator->getNew();

        $newUser->setPassword(
            $this->passwordHasher->hashPassword(new User($newUser), $password),
            $command->createdBy(),
            $this->clock
        );

        $this->userRepository->addUser($newUser);

        $activation = new UserActivation(
            $command->activationTokenId(),
            $newUser->id(),
            $this->clock->currentDateTime()
        );

        $this->userActivationRepository->addActivation($activation);

        $this->emailSender->sendInvitationEmail(
            $command->email(),
            $command->firstName(),
            $password,
            $command->activationTokenId()->toRfc4122()
        );
    }
}
