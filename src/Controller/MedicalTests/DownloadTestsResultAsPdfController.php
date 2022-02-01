<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Infrastructure\TestsResultPdfGenerator;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class DownloadTestsResultAsPdfController extends AbstractController
{
    public function __construct(
        private TestsResultPdfGenerator $generator
    ) {
    }

    #[Route(path: '/current-user/test/{id}/pdf', name: 'tests-result-pdf-send', methods: 'POST')]
    public function activateUser(string $id): PdfResponse
    {
        return new PdfResponse(
            $this->generator->getTestsResultAsPdfOutput(UuidV4::fromString($id)),
            sprintf('%s.pdf', $id)
        );
    }
}
