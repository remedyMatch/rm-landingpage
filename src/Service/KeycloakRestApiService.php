<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class KeycloakRestApiService implements KeycloakRestApiServiceInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $keycloakUrl;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $realm;

    public function __construct(ParameterBagInterface $params)
    {
        $this->keycloakUrl = $params->get('app.keycloak.url');
        $this->username = $params->get('app.keycloak.user');
        $this->password = $params->get('app.keycloak.password');
        $this->clientId = $params->get('app.keycloak.client_id');
        $this->secret = $params->get('app.keycloak.client_secret');
        $this->realm = $params->get('app.keycloak.realm');
        $this->accessToken = $this->fetchAccessToken();
        $this->client = new Client([
            'base_uri' => $this->keycloakUrl.'/auth/',
            'defaults' => [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
            ],
        ]);
    }

    public function fetchAccessToken(): string
    {
        $client = new Client();
        $response = $client->request('POST', $this->keycloakUrl.'/auth/realms/master/protocol/openid-connect/token',
            [
                'auth' => ['remedymatch', 'development'],
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => 'password',
                    'client_id' => $this->clientId,
                    'secret' => $this->secret,
                ],
            ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $token = $data['access_token'];

        return $token;
    }

    public function getUsers(?string $email = null): array
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('GET', 'admin/realms/'.$this->realm.'/users',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'query' => [
                    'email' => $email,
                ],
            ]);

        return json_decode($response->getBody()->getContents());
    }

    public function updateUser($id, $user): string
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('PUT', 'admin/realms/'.$this->realm.'/users/'.$id,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'json' => $user,
            ]);

        return $response->getBody()->getContents();
    }

    public function addUser(array $user): string
    {
        $response = $this->client->request('POST', 'admin/realms/'.$this->realm.'/users',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'json' => $user,
            ]);

        return $response->getBody()->getContents();
    }

    public function addGroup(array $group): string
    {
        $response = $this->client->request('POST', 'admin/realms/'.$this->realm.'/groups',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'json' => $group,
            ]);

        return $response->getBody()->getContents();
    }
}
