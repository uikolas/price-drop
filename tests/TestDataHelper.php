<?php

declare(strict_types=1);

namespace Tests;

trait TestDataHelper
{
    private function getTestData(string $filename): string
    {
        $data = \file_get_contents(__DIR__ . '/TestData/'. $filename);

        if ($data === false) {
            throw new \InvalidArgumentException('No file: ' . $filename);
        }

        return $data;
    }
}
