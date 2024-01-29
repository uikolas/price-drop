<?php

declare(strict_types=1);

namespace App\Exceptions;

class FailedHttpRequestException extends \Exception
{
    public static function create(string $url, int $statusCode): self
    {
        return new self(
            \sprintf('Failed HTTP call to: %s. Got status code: %d', $url, $statusCode),
            $statusCode,
        );
    }
}
