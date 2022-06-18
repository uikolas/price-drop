<?php

declare(strict_types=1);

namespace App\Client;

use App\Exceptions\FailedHttpRequestException;

interface HttpClientInterface
{
    /**
     * @throws FailedHttpRequestException
     */
    public function get(string $url): string;
}
