<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Exception\KeycloakException;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class KeycloakManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const GROUP_NEW = 'neu';
    const GROUP_APPROVED = 'freigegeben';

    /**
     * @var KeycloakRestApiServiceInterface
     */
    private $keycloakRestApi;

    public function __construct(KeycloakRestApiServiceInterface $keycloakRestApiService)
    {
        $this->keycloakRestApi = $keycloakRestApiService;
    }

    /**
     * @throws KeycloakException
     */
    public function createAccount(Account $account): void
    {
        $group = 'neu';

        $user = [
            'email' => $account->getEmail(),
            'username' => $account->getEmail(),
            'firstName' => $account->getFirstname(),
            'lastName' => $account->getLastname(),
            'enabled' => false,
            'emailVerified' => false,
            'credentials' => [
                [
                    'type' => 'password',
                    'value' => $account->getPassword() ?? '',
                    'temporary' => false,
                ],
            ],
            'attributes' => [
                'company' => $account->getCompany() ?? '',
                'company-type' => $account->getType() ?? '',
                'street' => $account->getStreet(),
                'housenumber' => $account->getHousenumber(),
                'zipcode' => $account->getZipcode(),
                'city' => $account->getCity(),
                'phone' => $account->getPhone(),
                'country' => 'Deutschland',
            ],
            'groups' => [
                self::GROUP_NEW,
            ],
        ];

        try {
            $this->keycloakRestApi->addUser($user);
        } catch (ClientException $clientException) {
            $this->logger->error('Add user request to keycloak failed', [
                'user' => $user,
            ]);
            throw new KeycloakException('User could not be created in keycloak', 85345164, $clientException);
        }
    }

    public function approveAccount(string $email): void
    {
        $users = $this->keycloakRestApi->getUsers($email);
        if (0 === count($users)) {
            throw new \Exception('Could not find user');
        }

        $groups = $this->keycloakRestApi->getGroups();

        $groupIDOld = 0;
        $groupIDNew = 0;

        foreach ($groups as $group) {
            if (0 == strcmp($group->name, self::GROUP_NEW)) {
                $groupIDOld = $group->id;
            } elseif (0 == strcmp($group->name, self::GROUP_APPROVED)) {
                $groupIDNew = $group->id;
            }
        }
        $this->keycloakRestApi->deleteUserGroup($users[0]->id, $groupIDOld);
        $this->keycloakRestApi->addUserGroup($users[0]->id, $groupIDNew);
    }

    public function verifyEmailAccount(string $email): void
    {
        $users = $this->keycloakRestApi->getUsers($email);
        $users[0]->emailVerified = true;
        $this->keycloakRestApi->updateUser($users[0]->id, $users[0]);
    }
}
