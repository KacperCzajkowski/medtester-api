<?php

declare(strict_types=1);

namespace App\MedicalTests\Application;

use App\Core\Domain\Clock;
use App\MedicalTests\Domain\TestsResult;
use App\MedicalTests\Domain\TestsResultRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SaveTestsResult implements MessageHandlerInterface
{
    public function __construct(
        private TestsResultRepository $repository,
        private Clock $clock
    ) {
    }

    public function __invoke(SaveTestsResult\Command $command): void
    {
        $result = $this->repository->fetchTestsResultById($command->testId());

        if (!$result) {
            $newTestsResult = TestsResult::fromCommandAndClock($command, $this->clock);
            $this->repository->addNewTestsResult($newTestsResult);

            return;
        }

        $result->updateFromCommandAndClock($command, $this->clock);
    }
}
