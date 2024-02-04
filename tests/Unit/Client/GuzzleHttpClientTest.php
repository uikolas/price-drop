<?php

declare(strict_types=1);

namespace Tests\Unit\Client;

use App\Client\GuzzleHttpClient;
use App\Exceptions\FailedHttpRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClientTest extends TestCase
{
    private Client&MockObject $client;

    private GuzzleHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->httpClient = new GuzzleHttpClient($this->client);
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
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                    ],
                    'timeout' => 10,
                    'http_errors' => false,
                    'allow_redirects' => true,
                ]
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

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                    ],
                    'timeout' => 10,
                    'http_errors' => false,
                    'allow_redirects' => true,
                ]
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

        $this->client->expects(self::once())
            ->method('get')
            ->with(
                'url',
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                    ],
                    'timeout' => 10,
                    'http_errors' => false,
                    'allow_redirects' => true,
                ]
            )
            ->willReturn(new Response(status: 404, body: 'response'));

        $this->httpClient->get('url');
    }
}
