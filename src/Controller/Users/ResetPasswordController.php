<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Users\Application\UseCase\ResetPassword;
use App\Users\Infrastructure\FormType\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route(path: 'open/reset-password', name: 'reset-password', methods: ['POST'])]
    public function resetPassword(Request $request): JsonResponse
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }

        $this->dispatchMessage(new ResetPassword\Command($form->getData()['email']));

        return $this->json(null);
    }
}
