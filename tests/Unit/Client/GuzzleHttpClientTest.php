<?php

declare(strict_types=1);

namespace Tests\Unit\Client;

use App\Client\GuzzleHttpClient;
use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class GuzzleHttpClientTest extends TestCase
{
    private Client&MockObject $client;

    private LoggerInterface&MockObject $logger;

    private GuzzleHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpClient = new GuzzleHttpClient($this->client, $this->logger);
    }


    public function testGet(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
                    ]
                ]
            )
            ->willReturn(new Response(body: 'response'));

        self::assertSame(
            'response',
            $this->httpClient->get('url')
        );
    }

    public function testCatchAndThrowException(): void
    {
        $this->expectExceptionObject(
            FailedHttpRequestException::create('url')
        );

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
                    ]
                ]
            )
            ->willThrowException(
                new ClientException(
                    '',
                    $this->createMock(RequestInterface::class),
                    $this->createMock(ResponseInterface::class)
                )
            );

        $this->logger->expects(self::once())->method('error');

        $this->httpClient->get('url');
    }
}
