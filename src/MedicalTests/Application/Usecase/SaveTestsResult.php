<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase;

use App\Core\Domain\Clock;
use App\MedicalTests\Application\Exceptions\TestsResultNotFoundException;
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
        $result = $this->repository->fetchTestsResultInProgressByLabWorkerId($command->laboratoryWorkerId());

        if (!$result) {
            throw TestsResultNotFoundException::byLabWorkerId($command->laboratoryWorkerId());
        }

        $result->updateFromCommandAndClock($command, $this->clock);
    }
}
