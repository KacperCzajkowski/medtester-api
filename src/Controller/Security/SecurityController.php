<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(Request $request): JsonResponse
    {
        return $this->json($this->getUser());
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(Request $request): JsonResponse
    {
        return $this->json(null);
    }

    #[Route(path: '/login-status', name: 'login_status', methods: 'GET')]
    public function loggedInUser(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $result = [
            'id' => $user->id()->toRfc4122(),
            'email' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        return $this->json($result, $this->getUser() ? 200 : 403);
    }
}
