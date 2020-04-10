<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\SlackNotifierServiceInterface;
use Psr\Log\LoggerInterface;

final class TestSlackNotifierService implements SlackNotifierServiceInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendNotification(string $message): void
    {
        $this->logger->info('Mocked Slack message', [
            'message' => $message,
        ]);
    }
}
