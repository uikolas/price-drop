<?php

namespace App\Console\Commands;

use App\Product;
use App\Services\UpdateProductsService;
use Illuminate\Console\Command;

class UpdateProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update products';

    /**
     * @var UpdateProductsService
     */
    private $updateProductsService;

    /**
     * Create a new command instance.
     *
     * @param UpdateProductsService $updateProductsService
     */
    public function __construct(UpdateProductsService $updateProductsService)
    {
        parent::__construct();
        $this->updateProductsService = $updateProductsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::all();

        $this->updateProductsService->updateAndNotify($products);

        $this->info('Products updated');
    }
}
