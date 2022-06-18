<?php

declare(strict_types=1);

namespace App\Client;

use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class GuzzleHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger
    ) {
    }

    public function get(string $url): string
    {
        try {
            $response = $this->client->get(
                $url,
                [
                    'headers' => $this->getHeaders(),
                ]
            );
        } catch (GuzzleException $exception) {
            $this->logger->error(
                'Failed http call',
                [
                    'url' => $url,
                    'status' => $exception->getCode(),
                    'exception' => $exception,
                ]
            );

            throw FailedHttpRequestException::create($url);
        }

        return $response->getBody()->getContents();
    }

    /**
     * @return string[]
     */
    private function getHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
        ];
    }
}
