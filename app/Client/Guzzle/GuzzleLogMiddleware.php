<?php

declare(strict_types=1);

namespace App\Client\Guzzle;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class GuzzleLogMiddleware
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request) {
                    $content = (string) $response->getBody();

                    $this->logger->info(
                        'Guzzle HTTP request',
                        [
                            'request' => [
                                'method' => $request->getMethod(),
                                'url' => (string) $request->getUri(),
                                'headers' => $request->getHeaders(),
                            ],
                            'response' => [
                                'statusCode' => $response->getStatusCode(),
                                'body' => strlen($content) > 500 ? \mb_substr($content, 0, 500) : $content,
                            ],
                        ]
                    );

                    return $response;
                }
            );
        };
    }
}
