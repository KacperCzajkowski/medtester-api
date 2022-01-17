<?php

declare(strict_types=1);

namespace App\MedicalTests\Infrastructure;

use App\MedicalTests\Domain\TestsResultRepository;
use App\Users\Domain\UserRepository;
use Knp\Snappy\Pdf;
use Symfony\Component\Uid\UuidV4;
use Twig\Environment;

class TestsResultPdfGenerator
{
    public function __construct(
        private Pdf $pdf,
        private Environment $twig,
        private TestsResultRepository $testsResultRepository
    ) {}

    public function getTestsResultAsPdfOutput(UuidV4 $testId): string
    {
        $test = $this->testsResultRepository->getExtendedTestsResultDetails($testId);

        $content = $this->twig->render('pdf-template.html.twig', [
            'user' => $test->userDetails(),
            'all_tests' => $test->results(),
            'lab' => $test->laboratoryDetails(),
            'testDetails' => $test
        ]);

        return $this->pdf->getOutputFromHtml($content);
    }
}