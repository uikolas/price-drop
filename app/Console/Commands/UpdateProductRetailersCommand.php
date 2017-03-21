<?php

namespace App\Console\Commands;

use App\Manager\UpdatePriceManager;
use App\Product;
use App\ProductRetailer;
use App\Services\UpdateProductRetailersService;
use Illuminate\Console\Command;

class UpdateProductRetailersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product_retailers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Update product retailers';

    /**
     * @var UpdateProductRetailersService
     */
    private $updateProductRetailersService;

    /**
     * UpdatePriceCommand constructor.
     * @param UpdateProductRetailersService $updateProductRetailersService
     */
    public function __construct(UpdateProductRetailersService $updateProductRetailersService)
    {
        parent::__construct();
        $this->updateProductRetailersService = $updateProductRetailersService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productRetailers = ProductRetailer::all();

        $this->updateProductRetailersService->update($productRetailers);

        $this->info('Product retailers updated.');
    }
}
