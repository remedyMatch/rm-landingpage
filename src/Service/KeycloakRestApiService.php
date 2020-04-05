<?php

namespace App\Service;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class KeycloakRestApiService
 * @package App\Service
 *
 *
 * Jeder User erhält eine eigene Gruppe --> Privatperson Mailadresse als gruppenname
 * -
 *
 */
class KeycloakRestApiService
{
    /**
     * @var ParameterBagInterface
     */
    protected $params;
    /** @var Client */
    private $client;
    /** @var string */
    private $accessToken;
    /** @var string */
    private $keycloakUrl;
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $clientId;
    /** @var string */
    private $secret;

    /**
     * KeycloakRestApiService constructor.
     * @param ParameterBagInterface $params
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
            'base_uri' => $this->keycloakUrl . '/auth/',
            'defaults' => [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ]
            ]
        ]);
    }

    /**
     * @return string Token
     */
    public function fetchAccessToken()
    {
        $client = new Client();
        $response = $client->request('POST', $this->keycloakUrl . '/auth/realms/master/protocol/openid-connect/token',
            [
                'auth' => ['remedymatch', 'development'],
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => 'password',
                    'client_id' => $this->clientId,
                    'secret' => $this->secret,
                ]
            ]);
        $data = json_decode($response->getBody(), true);
        $token = $data['access_token'];
        return $token;
    }

    /**
     * @param null $email
     * @return array
     */
    public function getUsers($email = null): array
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('GET', 'admin/realms/master/users',
            [
                //'auth' => ['remedymatch', 'development'],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'query' => [
                    'email' => $email
                ]
            ]);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param $ID,
     * @param $user
     * @return array
     */
    public function updateUser($ID,$user)
    {
        $this->accessToken = $this->fetchAccessToken();
        $response = $this->client->request('PUT', 'admin/realms/master/users/'.$ID,
            [
                //'auth' => ['remedymatch', 'development'],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'json' => $user
            ]);
        return json_decode($response->getBody()->getContents());
    }
    /**
     * @param array $user
     * @return string
     * @throws ClientException
     */
    public function addUser(array $user)
    {
        $response = $this->client->request('POST', 'admin/realms/master/users',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'json' => $user
            ]);
           
        return $response->getBody()->getContents();
    }

    /**
     * @param array $group
     * @return string
     */
    public function addGroup(array $group)
    {
        $response = $this->client->request('POST', 'admin/realms/master/groups',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'json' => $group
            ]);
        return $response->getBody()->getContents();
    }
}