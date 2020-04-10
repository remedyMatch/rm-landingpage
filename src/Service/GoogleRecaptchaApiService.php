<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GoogleRecaptchaApiService implements GoogleRecaptchaApiServiceInterface
{
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var string
     */
    private $secret;

    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(HttpClientInterface $client, string $googleSecret)
    {
        $this->client = $client;
        $this->secret = $googleSecret;
    }

    /**
     * @throws ExceptionInterface
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
