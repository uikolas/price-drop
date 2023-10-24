<?php

declare(strict_types=1);

namespace App\Exceptions;

class FailedHttpRequestException extends \Exception
{
    public static function create(string $url, int $statusCode): self
    {
        return new self(
            \sprintf('Failed http call to: %s with status code: %d', $url, $statusCode),
            $statusCode,
        );
    }
}
