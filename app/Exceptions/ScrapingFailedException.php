<?php

declare(strict_types=1);

namespace App\Exceptions;

class ScrapingFailedException extends \Exception
{
    public static function create(\Throwable $exception): self
    {
        return new self('Failed to scrap data', 0, $exception);
    }
}
