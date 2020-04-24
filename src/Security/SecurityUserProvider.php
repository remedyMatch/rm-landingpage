<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class SecurityUserProvider implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): SecurityUser
    {
        return new SecurityUser($username);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return SecurityUser::class === $class;
    }
}
