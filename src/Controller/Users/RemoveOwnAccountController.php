<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Security\User;
use App\Users\Application\UseCase\RemoveUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveOwnAccountController extends AbstractController
{
    #[Route(path: '/current-user/remove-account', name: 'remove-own-account', methods: ['DELETE'])]
    public function removeOwnAccount(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $this->dispatchMessage(new RemoveUser\Command(
            $user->id(),
            $user->id()
        ));

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
