<?php

declare(strict_types=1);

namespace App\Client;

use Psr\Log\LoggerInterface;

class LoggingHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function get(string $url): string
    {
        $response = $this->client->get($url);

        $this->logger->info(
            'HTTP request',
            [
                'url' => $url,
                'response_content' => \mb_substr($response, 0, 500),
            ],
        );

        return $response;
    }
}
