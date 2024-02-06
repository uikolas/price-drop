<?php

declare(strict_types=1);

namespace Tests\Unit\Client;

use App\Client\Guzzle\GuzzleFactory;
use App\Client\GuzzleHttpClient;
use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClientTest extends TestCase
{
    private Client&MockObject $client;

    private GuzzleFactory&MockObject $guzzleFactory;

    private GuzzleHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->guzzleFactory = $this->createMock(GuzzleFactory::class);
        $this->httpClient = new GuzzleHttpClient($this->guzzleFactory);
    }

    public function testGet(): void
    {
        $this->guzzleFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->client);

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                $this->callback(function(array $options) {
                    $headers = $options['headers'];

                    self::assertArrayHasKey('User-Agent', $headers);
                    self::assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8', $headers['Accept']);
                    self::assertSame('gzip, deflate', $headers['Accept-Encoding']);

                    return true;
                })
            )
            ->willReturn(new Response(body: 'response'));

        self::assertEquals(
            'response',
            $this->httpClient->get('url')
        );
    }

    public function testCatchAndThrowException(): void
    {
        $this->expectExceptionObject(
            FailedHttpRequestException::createWithException(
                'url',
                    new ClientException(
                    '',
                    $this->createMock(RequestInterface::class),
                    $this->createMock(ResponseInterface::class)
                ),
            )
        );

        $this->guzzleFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->client);

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                $this->callback(function(array $options) {
                    $headers = $options['headers'];

                    self::assertArrayHasKey('User-Agent', $headers);
                    self::assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8', $headers['Accept']);
                    self::assertSame('gzip, deflate', $headers['Accept-Encoding']);

                    return true;
                })
            )
            ->willThrowException(
                new ClientException(
                    '',
                    $this->createMock(RequestInterface::class),
                    $this->createMock(ResponseInterface::class)
                )
            );

        $this->httpClient->get('url');
    }

    public function testCatchBadStatusCodeAndThrowException(): void
    {
        $this->expectExceptionObject(
            FailedHttpRequestException::createWithStatusCode(
                'url',
                404,
            )
        );

        $this->guzzleFactory->expects(self::once())
            ->method('create')
            ->willReturn($this->client);

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                $this->callback(function(array $options) {
                    $headers = $options['headers'];

                    self::assertArrayHasKey('User-Agent', $headers);
                    self::assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8', $headers['Accept']);
                    self::assertSame('gzip, deflate', $headers['Accept-Encoding']);

                    return true;
                })
            )
            ->willReturn(new Response(status: 404, body: 'response'));

        $this->httpClient->get('url');
    }
}
