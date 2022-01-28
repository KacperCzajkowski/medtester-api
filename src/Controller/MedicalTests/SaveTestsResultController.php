<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Application\Usecase\SaveTestsResult;
use App\MedicalTests\Infrastructure\FormTypes\SaveTestsResultType;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

class SaveTestsResultController extends AbstractController
{
    #[Route(path: '/lab-worker/test', name: 'tests-result-saving', methods: 'POST')]
    public function activateUser(Request $request): JsonResponse
    {
        $form = $this->createForm(SaveTestsResultType::class);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $form->getData();

        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();

        try {
            $this->dispatchMessage(new SaveTestsResult\Command(
                laboratoryWorkerId: $loggedInUser->id(),
                status: $data['status'],
                results: $data['results']
            ));

            return $this->json(null);
        } catch (HandlerFailedException $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
