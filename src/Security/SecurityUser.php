<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface
{
    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $username;

    public function __construct(string $username, array $roles = [])
    {
        $this->username = $username;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
