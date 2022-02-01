<?php

declare(strict_types=1);

namespace App\MedicalTests\Application\Usecase;

use App\MedicalTests\Domain\SingleTest;
use App\MedicalTests\Domain\TestsResultRepository;
use App\MedicalTests\Domain\TestTemplateRepository;
use App\Users\Application\Exception\IllegalArgumentException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddTemplateToResult implements MessageHandlerInterface
{
    public function __construct(
        private TestTemplateRepository $templateRepository,
        private TestsResultRepository $testsResultRepository
    ) {
    }

    public function __invoke(AddTemplateToResult\Command $command): void
    {
        $test = $this->testsResultRepository->fetchTestsResultInProgressByLabWorkerId($command->labWorkerId());
        $template = $this->templateRepository->findTemplateById($command->templateId());

        if (!($test && $template)) {
            throw new IllegalArgumentException();
        }

        $test->addNewSingleTest(
            SingleTest::fromTemplate($template)
        );
    }
}
