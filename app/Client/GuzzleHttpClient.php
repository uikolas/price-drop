<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Guzzle\GuzzleFactory;
use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class GuzzleHttpClient implements HttpClientInterface
{
    private const USER_AGENTS = [
        // Firefox on macOS.
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
        // Chrome on macOS.
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
        // Firefox on Windows.
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0',
    ];

    public function __construct(
        private readonly GuzzleFactory $guzzleFactory,
    ) {
    }

    public function get(string $url): string
    {
        try {
            $client = $this->guzzleFactory->create();

            $response = $client->get(
                $url,
                [
                    'headers' => $this->getHeaders(),
                ]
            );
        } catch (GuzzleException $exception) {
            throw FailedHttpRequestException::createWithException($url, $exception);
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode !== Response::HTTP_OK) {
            throw FailedHttpRequestException::createWithStatusCode($url, $statusCode);
        }

        return (string) $response->getBody();
    }

    /**
     * @return string[]
     */
    private function getHeaders(): array
    {
        $randomUserAgent = self::USER_AGENTS[array_rand(self::USER_AGENTS)];

        return [
            'User-Agent' => $randomUserAgent,
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
        ];
    }
}
