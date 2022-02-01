<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase;

use App\Core\Domain\Clock;
use App\Mailer\Application\EmailSender;
use App\MedicalTests\Application\Exceptions\TestsResultNotFoundException;
use App\MedicalTests\Domain\TestsResultRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SaveTestsResult implements MessageHandlerInterface
{
    public function __construct(
        private TestsResultRepository $repository,
        private Clock $clock,
        private EmailSender $emailSender,
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(SaveTestsResult\Command $command): void
    {
        $result = $this->repository->fetchTestsResultInProgressByLabWorkerId($command->laboratoryWorkerId());

        if (!$result) {
            throw TestsResultNotFoundException::byLabWorkerId($command->laboratoryWorkerId());
        }

        $result->updateFromCommandAndClock($command, $this->clock);

        if ($result->isDone()) {
            $user = $this->userRepository->findUserById($result->userId());
            $this->emailSender->sendEmailWithTestsResultAsPdf($result->id(), $user->email(), $user->firstName());
        }
    }
}
