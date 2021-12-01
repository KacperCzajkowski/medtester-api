<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Users\Application\UseCase\ActivateUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

class ActivateUserController extends AbstractController
{
    #[Route(path: '/open/activate/{tokenId}', name: 'account-activation', methods: 'GET')]
    public function activateUser(string $tokenId): JsonResponse
    {
        try {
            $this->dispatchMessage(new ActivateUser\Command($tokenId));

            return $this->json(null);
        } catch (HandlerFailedException $exception) {
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }
    }
}
