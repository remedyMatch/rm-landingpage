<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GoogleRecaptchaApiService
{
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    /** @var ParameterBagInterface */
    protected $params;
    /** @var string */
    protected $secret;
    /** @var HttpClient */
    private $client;

    /**
     * KeycloakRestApiService constructor.
     */
    public function __construct(ParameterBagInterface $params, HttpClientInterface $client)
    {
        $this->params = $params;
        $this->client = $client;
        $this->secret = $this->params->get('app.google_recaptcha.secret');
    }

    /**
     * @return float
     *
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function verify(string $token): ?float
    {
        $response = $this->client->request('POST', self::VERIFY_URL, [
            'body' => [
                'secret' => $this->secret,
                'response' => $token,
            ],
        ]);
        $data = $response->toArray();

        return !$data['success'] ? null : $data['score'];
    }
}
