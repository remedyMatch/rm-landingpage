<?php

declare(strict_types=1);

namespace App\Service;

use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

final class SlackNotifierService implements SlackNotifierServiceInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(string $slackToken, LoggerInterface $logger)
    {
        $this->client = ClientFactory::create($slackToken);
        $this->logger = $logger;
    }

    public function sendNotification(string $message): void
    {
        try {
            $this->client->chatPostMessage([
                'username' => 'remedybot',
                'channel' => 'sandkasten',
                'text' => $message,
            ]);
        } catch (SlackErrorResponse $e) {
            $this->logger->warning('Slack message could not be sent');
        }
    }
}
