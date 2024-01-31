<?php

declare(strict_types=1);

namespace App\Exceptions;

class FailedHttpRequestException extends \Exception
{
    private ?int $statusCode;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        ?int $statusCode = null,
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    public static function createWithStatusCode(string $url, ?int $statusCode): self
    {
        return new self(
            \sprintf('Failed HTTP call to: %s. Got status code: %s', $url, $statusCode),
            0,
            null,
            $statusCode,
        );
    }

    public static function createWithException(string $url, \Throwable $throwable): self
    {
        return new self(
            \sprintf('Failed HTTP call to: %s.', $url),
            0,
            $throwable,
        );
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
