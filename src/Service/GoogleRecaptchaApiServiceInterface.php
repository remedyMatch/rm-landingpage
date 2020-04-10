<?php

declare(strict_types=1);

namespace App\Service;

interface GoogleRecaptchaApiServiceInterface
{
    public function verify(string $token): ?float;
}
