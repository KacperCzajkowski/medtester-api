<?php

declare(strict_types=1);

namespace App\Security;

use App\Core\Domain\Email;
use App\Users\Application\UseCase\LaboratoryWorkersRepository;
use App\Users\Application\UseCase\PatientRepository;
use App\Users\Application\UseCase\SystemAdminRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(
        private PatientRepository $patients,
        private LaboratoryWorkersRepository $workers,
        private SystemAdminRepository $admins
    ) { }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        try {
            $email = new Email($username);

            $user = $this->admins->findUserByEmail($email);

            if ($user) {
                return new User($user);
            }

            $user = $this->workers->findWorkerByEmail($email);

            if ($user) {
                return new User($user);
            }

            $user = $this->patients->findPatientByEmail($email);

            if ($user) {
                return new User($user);
            }

            throw new UserNotFoundException();
        } catch (UserNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('%s not found', $username));
        }
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException  if the user is not supported
     * @throws UsernameNotFoundException if the user is not found
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException('User does not match');
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
