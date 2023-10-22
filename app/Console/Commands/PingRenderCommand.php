<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingRenderCommand extends Command
{
    protected $signature = 'ping:render';

    public function handle(): int
    {
        $this->info('Starting ping...');

        Http::get('https://price-drop.free.beeceptor.com');

        return self::SUCCESS;
    }
}
