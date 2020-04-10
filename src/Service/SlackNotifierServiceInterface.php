<?php

declare(strict_types=1);

namespace App\Service;

interface SlackNotifierServiceInterface
{
    public function sendNotification(string $message): void;
}
