<?php

namespace App\Services;

use App\ProductRetailer;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Collection;

class UpdateProductRetailersService
{
    /**
     * @var ParserService
     */
    private $parserService;

    /**
     * @var Log
     */
    private $logger;

    /**
     * UpdateProductRetailersService constructor.
     * @param ParserService $parserService
     * @param Log $logger
     */
    public function __construct(ParserService $parserService, Log $logger)
    {
        $this->parserService = $parserService;
        $this->logger        = $logger;
    }

    /**
     * @param Collection|ProductRetailer[] $productRetailers
     */
    public function update(Collection $productRetailers)
    {
        foreach ($productRetailers as $productRetailer) {
            try {
                $retailer = $productRetailer->getRetailer();

                $parse = $this->parserService->parseProductRetailer($productRetailer->url, $retailer->retailer_code);

                $productRetailer->price = $parse->getPrice() ? $parse->getPrice()->getAmount() : null;
                $productRetailer->save();
            } catch (\Exception $exception) {
                $this->logger->warning($exception->getMessage());
            }
        }
    }
}
