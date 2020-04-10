<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\KeycloakRestApiServiceInterface;
use Psr\Log\LoggerInterface;

final class TestKeycloakRestApiService implements KeycloakRestApiServiceInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function fetchAccessToken(): string
    {
        $this->logger->info('Asked keycloak rest api service to fetch access token');

        return 'some-access-token';
    }

    public function getUsers(?string $email = null): array
    {
        $this->logger->info('Asked keycloak rest api service to get users', [
            'email' => $email,
        ]);

        return  [];
    }

    public function updateUser($id, $user): array
    {
        $this->logger->info('Asked keycloak rest api service to update user', [
            'id' => $id,
            'user' => $user,
        ]);

        return [];
    }

    public function addUser(array $user): string
    {
        $this->logger->info('Asked keycloak rest api service to add user', [
            'user' => $user,
        ]);

        return '';
    }

    public function addGroup(array $group): string
    {
        $this->logger->info('Asked keycloak rest api service to add group', [
            'group' => $group,
        ]);

        return '';
    }
}
