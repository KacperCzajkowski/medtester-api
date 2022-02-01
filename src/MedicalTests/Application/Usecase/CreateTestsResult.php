<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase;

use App\Core\Domain\Clock;
use App\Core\Domain\Exceptions\UserNotFoundException;
use App\MedicalTests\Domain\TestsResult;
use App\MedicalTests\Domain\TestsResultRepository;
use App\Users\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateTestsResult implements MessageHandlerInterface
{
    public function __construct(
        private TestsResultRepository $testsResultRepository,
        private UserRepository $userRepository,
        private Clock $clock
    ) {
    }

    public function __invoke(CreateTestsResult\Command $command): void
    {
        $user = $this->userRepository->findByPesel($command->userPesel());

        if (!$user) {
            throw UserNotFoundException::byPesel($command->userPesel());
        }

        $result = new TestsResult(
            $command->id(),
            $user->id(),
            $command->creatorId(),
            'in-progress',
            $this->clock->currentDateTime(),
            $this->clock->currentDateTime(),
            []
        );

        $this->testsResultRepository->addNewTestsResult($result);
    }
}
