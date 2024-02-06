<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Guzzle;

use App\Client\Guzzle\GuzzleLogMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GuzzleLogMiddlewareTest extends TestCase
{
    public function testInvoke(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $middleware = new GuzzleLogMiddleware($logger);

        $mockHandler = new MockHandler([new Response(body: 'Mocked Response')]);

        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push($middleware);

        $client = new Client(['handler' => $handlerStack]);

        $logger->expects(self::once())
            ->method('info')
            ->with(
                'Guzzle HTTP request',
                [
                    'request' => [
                        'method' => 'GET',
                        'url' => 'https://example.com/api',
                        'headers' => [
                            'User-Agent' => ['GuzzleHttp/7'],
                            'Host' => ['example.com'],
                        ],
                    ],
                    'response' => [
                        'statusCode' => 200,
                        'body' => 'Mocked Response',
                    ],
                ]
            );

        $response = $client->get('https://example.com/api');

        $this->assertEquals('Mocked Response', (string) $response->getBody());
    }
}
