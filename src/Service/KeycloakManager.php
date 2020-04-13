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
        if (null === $account->getCompany()) {
            $groupname = sprintf('Privatperson-%s', $account->getEmail());
        } else {
            $groupname = $account->getCompany();
        }

        $group = [
            'name' => $groupname,
        ];

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
                'status' => 'NEU',
                'country' => 'Deutschland',
            ],
            'groups' => [
                $group['name'],
            ],
        ];

        try {
            $this->keycloakRestApi->addGroup($group);
        } catch (\Exception $exception) {
            $this->logger->error('Add group request to keycloak failed', [
                'group' => $group,
            ]);
        }

        try {
            $this->keycloakRestApi->addUser($user);
        } catch (ClientException $clientException) {
            $this->logger->error('Add user request to keycloak failed', [
                'user' => $user,
            ]);
            throw new KeycloakException('User could not be created in keycloak');
        }
    }

    public function verifyAccount(string $email): void
    {
        $users = $this->keycloakRestApi->getUsers($email);
        $users[0]->attributes->status = 'verifiziert';
        $users[0]->emailVerified = true;
        $this->keycloakRestApi->updateUser($users[0]->id, $users[0]);
    }
}
