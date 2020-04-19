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

        $user = new \stdClass();
        $user->attributes = new \stdClass();
        $user->id = 1;

        return  [$user];
    }

    public function updateUser($id, $user): string
    {
        $this->logger->info('Asked keycloak rest api service to update user', [
            'id' => $id,
            'user' => $user,
        ]);

        return '';
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

    public function getGroups(): array
    {
        $this->logger->info('Asked keycloak rest api service to get groups');

        $group = new \stdClass();
        $group->id = 1;

        return  [$group];
    }

    public function addUserGroup($userID, $groupID): string
    {
        $this->logger->info('Asked keycloak rest api service to add user group');

        return  '';
    }

    public function deleteUserGroup($userID, $groupID): string
    {
        $this->logger->info('Asked keycloak rest api service to delete user group');

        return  '';
    }
}
