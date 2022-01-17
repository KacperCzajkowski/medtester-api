<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Application\Query\TestsResultQuery;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class TestsResultQueryController extends AbstractController
{
    public function __construct(private TestsResultQuery $resultQuery)
    {
    }

    #[Route(path: '/lab-worker/details', name: 'tests-result-details', methods: 'GET')]
    public function activateUser(Request $request): JsonResponse
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();

        return $this->json(
            $this->resultQuery->findTestsResultInProgressByLabWorkerId($loggedInUser->id())
        );
    }
//todo testy
    #[Route(path: '/current-user/results', name: 'all-tests-results', methods: 'GET')]
    public function fetchMyLabDetails(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->json(
            $this->resultQuery->fetchAllTestsResultsForUser($user->id())
        );
    }
//todo test
    #[Route(path: '/current-user/results/{resultId}', name: 'single-result-details', methods: 'GET')]
    public function fetchSingleResultDetails(string $resultId): JsonResponse
    {
        return $this->json(
            $this->resultQuery->fetchResultById(UuidV4::fromString($resultId))
        );
    }
}
