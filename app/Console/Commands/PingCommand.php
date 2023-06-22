<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingCommand extends Command
{
    private const USER_AGENTS = [
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:100.0) Gecko/20100101 Firefox/100.0',
        'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246',
    ];

    protected $signature = 'ping';

    public function handle(): int
    {
        $this->info('Starting ping...');

        Http::withHeaders(
            [
                'User-Agent' => $this->getRandomUserAgent(),
            ]
        )
            ->get('https://price-drop.onrender.com');

        return self::SUCCESS;
    }

    private function getRandomUserAgent(): string
    {
        $collection = collect(self::USER_AGENTS);

        return $collection->random();
    }
}
