<?php

declare(strict_types=1);

namespace App\Client\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Log\LoggerInterface;

class GuzzleFactory
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function create(): Client
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(new GuzzleLogMiddleware($this->logger));

        return new Client(
            [
                'handler' => $handlerStack,
                'timeout' => 30,
                'connect_timeout' => 15,
                'http_errors' => false,
                'allow_redirects' => true,
            ]
        );
    }
}
