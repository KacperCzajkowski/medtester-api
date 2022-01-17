<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Security\User;
use App\Users\Application\Query\UsersQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailsController extends AbstractController
{
    public function __construct(private UsersQuery $usersQuery)
    {
    }

    #[Route(path: 'current-user/details', name: 'user-details', methods: ['GET'])]
    public function getUserDetails(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json(
            $this->usersQuery->fetchUserDetailsById($user->id())
        );
    }

    #[Route(path: 'lab-worker/users', name: 'users-to-tests', methods: ['GET'])]
    public function getUsersToTests(Request $request): JsonResponse
    {
        /** @var string $query */
        $query = $request->query->get('query', '') ?? '';

        return $this->json(
            $this->usersQuery->findUsersToTestByText($query)
        );
    }
}
