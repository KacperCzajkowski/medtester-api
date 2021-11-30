<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Security\User;
use App\Users\Application\Exception\WrongPasswordException;
use App\Users\Application\UseCase\ChangePassword;
use App\Users\Infrastructure\FormType\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    #[Route(path: '/current-user/change-password', name: 'change-password', methods: 'POST')]
    public function changePassword(Request $request): JsonResponse
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }

        $data = $form->getData();

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$this->passwordHasher->isPasswordValid($user, $data['oldPassword'])) {
            throw WrongPasswordException::byUserId($user->id());
        }

        $this->dispatchMessage(new ChangePassword\Command(
            $user->id(),
            $this->passwordHasher->hashPassword($user, $data['newPassword'])
        ));

        return $this->json(null);
    }
}
