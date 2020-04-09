<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class KeycloakRestApiService.
 *
 * Jeder User erhält eine eigene Gruppe --> Privatperson Mailadresse als gruppenname
 * -
 */
final class KeycloakRestApiService
{
    /**
     * @var ParameterBagInterface
     */
    protected $params;

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
     * KeycloakRestApiService constructor.
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->keycloakUrl = $this->params->get('app.keycloak.url');
        $this->username = $this->params->get('app.keycloak.user');
        $this->password = $this->params->get('app.keycloak.password');
        $this->clientId = $this->params->get('app.keycloak.client_id');
        $this->secret = $this->params->get('app.keycloak.client_secret');
        $this->accessToken = $this->fetchAccessToken();
        $this->client = new \GuzzleHttp\Client([
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
        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];

        return $token;
    }

    public function getUsers(?string $email = null): array
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('GET', 'admin/realms/master/users',
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

    public function updateUser($id, $user): array
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('PUT', 'admin/realms/master/users/'.$id,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'json' => $user,
            ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @throws ClientException
     */
    public function addUser(array $user): string
    {
        $response = $this->client->request('POST', 'admin/realms/master/users',
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
        $response = $this->client->request('POST', 'admin/realms/master/groups',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                ],
                'json' => $group,
            ]);

        return $response->getBody()->getContents();
    }
}
