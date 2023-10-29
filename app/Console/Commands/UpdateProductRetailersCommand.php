<?php

namespace App\Console\Commands;

use App\Jobs\ProcessProductRetailer;
use App\Models\Product;
use App\Models\ProductRetailer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateProductRetailersCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'retailers:update';

    protected $description = 'Update product retailer prices and notify';

    public function handle(): int
    {
        $this->info('Starting update...');
        $retailersCount = ProductRetailer::count();
        $this->info('Updating retailers: ' . $retailersCount);

        $bar = $this->output->createProgressBar($retailersCount);

        /** @var ProductRetailer $productRetailer */
        foreach (ProductRetailer::lazy() as $productRetailer) {
            $this->dispatch(new ProcessProductRetailer($productRetailer, true));
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Finished');

        return self::SUCCESS;
    }
}
