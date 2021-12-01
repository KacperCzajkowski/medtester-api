<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Security\User;
use App\Users\Application\UseCase\ChangeEmail;
use App\Users\Infrastructure\FormType\ChangeEmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangeEmailController extends AbstractController
{
    #[Route(path: '/current-user/change-email', name: 'change-email', methods: 'POST')]
    public function changePassword(Request $request): JsonResponse
    {
        $form = $this->createForm(ChangeEmailType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }

        $data = $form->getData();

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $this->dispatchMessage(new ChangeEmail\Command(
            $user->id(),
            $data['newEmail']
        ));

        return $this->json(null);
    }
}
