<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Mailer\Application\EmailSender;
use App\Security\User;
use App\Users\Application\Exception\IllegalArgumentException;
use App\Users\Domain\User as DomainUser;
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
        private EmailSender $emailSender
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
                $newUser->setLaboratoryId($labId);
            } else {
                throw IllegalArgumentException::byInvalidDataToCreateLaboratoryWorker($command->createdBy());
            }
        }

        $password = $this->passwordGenerator->getNew();

        $newUser->setPassword(
            $this->passwordHasher->hashPassword(new User($newUser), $password)
        );

        $this->userRepository->addUser($newUser);

        $this->emailSender->sendEmailWithNewPassword($command->email(), $command->firstName(), $password);
    }
}
