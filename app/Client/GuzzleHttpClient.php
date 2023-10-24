<?php

declare(strict_types=1);

namespace App\Client;

use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleHttpClient implements HttpClientInterface
{
    public function __construct(
        private readonly Client $client,
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
            throw FailedHttpRequestException::create(
                $url,
                $exception->getCode(),
            );
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
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
        ];
    }
}
