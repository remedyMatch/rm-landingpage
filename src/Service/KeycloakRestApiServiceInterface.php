<?php

declare(strict_types=1);

namespace App\Service;

interface KeycloakRestApiServiceInterface
{
    public function fetchAccessToken(): string;

    public function getUsers(?string $email = null): array;

    public function updateUser($id, $user): string;

    public function addUser(array $user): string;

    public function addGroup(array $group): string;

    public function getGroups(): array;

    public function deleteUserGroup($userID, $groupID): string;

    public function addUserGroup($userID, $groupID): string;
}
