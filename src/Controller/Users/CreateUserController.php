<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Core\Domain\Exceptions\EmailAlreadyUsedException;
use App\Security\User;
use App\Users\Application\Exception\IllegalArgumentException;
use App\Users\Application\UseCase\CreateUser;
use App\Users\Domain\User as DomainUser;
use App\Users\Infrastructure\FormType\CreateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class CreateUserController extends AbstractController
{
    private const ALLOWED_ROLES = [
        DomainUser::ROLES['ROLE_PATIENT'],
        DomainUser::ROLES['ROLE_LABORATORY_WORKER'],
    ];

    #[Route(path: '/lab-worker/create', name: 'create_user', methods: 'POST')]
    public function createUser(Request $request): JsonResponse
    {
        $form = $this->createForm(CreateUserType::class);

        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $this->json('Invalid request', Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var User $creator
         */
        $creator = $this->getUser();
        $data = $form->getData();

        if (!in_array($data['roles'], self::ALLOWED_ROLES, true)) {
            return $this->json(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if (!$this->hasCreatorPermissionToCreateUserWithRole($creator, $data['roles'])) {
            return $this->json(null, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $command = new CreateUser\Command(
            UuidV4::v4(),
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            [$data['roles']],
            $creator->id(),
            $data['pesel'],
            $data['gender'],
            UuidV4::v4()
        );

        if ($data['laboratoryId']) {
            $command->setLaboratoryId(Uuidv4::fromString($data['laboratoryId']));
        }

        try {
            $this->dispatchMessage($command);
        } catch (HandlerFailedException $exception) {
            if ($exception->getNestedExceptionOfClass(EmailAlreadyUsedException::class)) {
                return $this->json('Email already used', Response::HTTP_BAD_REQUEST);
            }

            if ($exception->getNestedExceptionOfClass(IllegalArgumentException::class)) {
                return $this->json(null, Response::HTTP_METHOD_NOT_ALLOWED);
            }

            throw $exception;
        }

        return $this->json(null, Response::HTTP_CREATED);
    }

    private function hasCreatorPermissionToCreateUserWithRole(User $creator, string $newUserRole): bool
    {
        $permissions = [
            'ROLE_PATIENT' => ['ROLE_LABORATORY_WORKER', 'ROLE_SYSTEM_ADMIN'],
            'ROLE_LABORATORY_WORKER' => ['ROLE_SYSTEM_ADMIN'],
        ];

        return $creator->hasNecessaryRole($permissions[$newUserRole]);
    }
}
