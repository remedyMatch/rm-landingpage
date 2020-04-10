<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\GoogleRecaptchaApiServiceInterface;
use Psr\Log\LoggerInterface;

final class TestGoogleRecaptchaApiService implements GoogleRecaptchaApiServiceInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function verify(string $token): ?float
    {
        $this->logger->info('Asked test google recaptcha service to verify token', [
            'token' => $token,
        ]);

        return 0.0;
    }
}
