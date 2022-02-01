<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Application\Query\TestTemplateQuery;
use App\MedicalTests\Application\Usecase\AddTemplateToResult;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

class AddTemplateToResultController extends AbstractController
{
    public function __construct(private TestTemplateQuery $templateQuery)
    {
    }

    #[Route(path: '/lab-worker/test/add/{templateId}', name: 'tests-result-template-add', methods: 'POST')]
    public function addTemplateToResult(string $templateId, Request $request): JsonResponse
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();

        try {
            $this->dispatchMessage(new AddTemplateToResult\Command(
                $loggedInUser->id()->toRfc4122(),
                $templateId
            ));

            return $this->json(null);
        } catch (HandlerFailedException $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: '/lab-worker/test/templates', name: 'tests-result-template-list', methods: 'GET')]
    public function fetchAllTemplates(Request $request): JsonResponse
    {
        return $this->json(
            $this->templateQuery->fetchAll()
        );
    }
}
