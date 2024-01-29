<?php

declare(strict_types=1);

namespace Tests\Unit\Client;

use App\Client\HttpClientInterface;
use App\Client\LoggingHttpClient;
use App\Exceptions\FailedHttpRequestException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggingHttpClientTest extends TestCase
{
    private HttpClientInterface&MockObject $client;

    private LoggerInterface&MockObject $logger;

    private LoggingHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpClient = new LoggingHttpClient($this->client, $this->logger);
    }

    public function testLogSuccessfulCall(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('url')
            ->willReturn('response');

        $this->logger->expects(self::once())
            ->method('info')
            ->with(
                'HTTP request',
                [
                    'url' => 'url',
                    'response_content' => 'response',
                ],
            );

        $this->httpClient->get('url');
    }

    public function testLogErrorCall(): void
    {
        $this->expectException(FailedHttpRequestException::class);

        $this->client->expects(self::once())
            ->method('get')
            ->with('url')
            ->willThrowException(
                new FailedHttpRequestException(),
            );

        $this->logger->expects(self::once())
            ->method('info')
            ->with(
                'HTTP request failed',
                [
                    'url' => 'url',
                    'status_code' => 0,
                ],
            );

        $this->httpClient->get('url');
    }
}
