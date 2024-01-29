<?php

declare(strict_types=1);

namespace App\Client;

use App\Exceptions\FailedHttpRequestException;
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
        try {
            $response = $this->client->get($url);
        } catch (FailedHttpRequestException $e) {
            $this->logger->info(
                'HTTP request failed',
                [
                    'url' => $url,
                    'status_code' => $e->getCode(),
                ],
            );

            throw $e;
        }

        $this->logger->info(
            'HTTP request',
            [
                'url' => $url,
                'response_content' => \mb_substr($response, 0, 100),
            ],
        );

        return $response;
    }
}
