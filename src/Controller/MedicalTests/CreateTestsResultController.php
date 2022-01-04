<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Application\Usecase\CreateTestsResult;
use App\MedicalTests\Infrastructure\FormTypes\CreateTestsResultType;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class CreateTestsResultController extends AbstractController
{
    //todo testy

    #[Route(path: '/lab-worker/test/create', name: 'tests-result-creating', methods: 'POST')]
    public function activateUser(Request $request): JsonResponse
    {
        $form = $this->createForm(CreateTestsResultType::class);

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
            $this->dispatchMessage(new CreateTestsResult\Command(
                UuidV4::v4()->toRfc4122(),
                $loggedInUser->id()->toRfc4122(),
                $data['userPesel']
            ));

            return $this->json(null);
        } catch (HandlerFailedException $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
