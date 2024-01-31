<?php

namespace App\Livewire;

use App\RetailerType;
use Livewire\Component;

class CreateProductRetailer extends Component
{
    public $product;

    public $url = '';

    public $selectedType = '';

    public $types = [];

    public function updatedUrl(): void
    {
        $hostname = parse_url($this->url, PHP_URL_HOST);
        $hostname = str_replace(['www.', '.lt', '.com'], '', $hostname);

        $this->selectedType = RetailerType::tryFrom($hostname) ?? RetailerType::SKYTECH;
    }

    public function render()
    {
        return view('livewire.create-product-retailer');
    }
}
