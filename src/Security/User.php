<?php

declare(strict_types=1);

namespace App\Security;

use App\Users\Domain\User as DomainUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private DomainUser $user;

    public function __construct(DomainUser $user)
    {
        $this->user = $user;
    }

    public function getRoles()
    {
        return $this->user->roles();
    }

    public function getPassword(): ?string
    {
        return $this->user->password();
    }

    public function getSalt()
    {
        return null;
    }

    public function id(): UuidV4
    {
        return $this->user->id();
    }

    public function getUsername()
    {
        return (string) $this->user->email();
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        /**
         * @var User $definedUser
         */
        $definedUser = $user;

        return ((string) $definedUser->id()) === ((string) $this->id()) && ($this->getUsername() === $definedUser->getUsername());
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->id(),
            'email' => $this->getUsername(),
            'roles' => $this->getRoles(),
        ];
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function hasRole(string $roleToFind): bool
    {
        $result = array_filter($this->getRoles(), static fn (string $role): bool => $role === $roleToFind);

        return !empty($result);
    }

    public function hasNecessaryRole(array $neededRoles): bool
    {
        $result = array_diff($neededRoles, $this->getRoles());

        return count($result) < count($neededRoles);
    }
}
