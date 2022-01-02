<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Users\Application\Query\UsersQuery;
use Pesel\Pesel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailsController extends AbstractController
{
    public function __construct(private UsersQuery $usersQuery)
    {
    }

    public function getUserDetails(): JsonResponse
    {
        return $this->json(null);
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

    //todo testy
//    #[Route(path: 'lab-worker/users/{pesel}', name: 'user-by-pesel', methods: ['GET'])]
//    public function getUserByPesel(string $pesel): JsonResponse
//    {
//        return $this->json(
//            $this->usersQuery->findPatientByPesel(new Pesel($pesel))
//        );
//    }
}
