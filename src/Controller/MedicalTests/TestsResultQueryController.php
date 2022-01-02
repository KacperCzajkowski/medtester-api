<?php

declare(strict_types=1);

namespace App\Controller\MedicalTests;

use App\MedicalTests\Application\Query\TestsResultQuery;
use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
