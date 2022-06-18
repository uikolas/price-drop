<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRetailerRequest;
use App\Jobs\ProcessProductRetailer;
use App\Models\Product;
use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductRetailerController extends Controller
{
    public function __construct()
    {
        // https://github.com/laravel/ideas/issues/1612
        $this->authorizeResource('App\Models\ProductRetailer,product', 'retailer,product');
    }

    public function create(Product $product): View
    {
        return view('retailers.create', ['product' => $product, 'types' => RetailerType::cases()]);
    }

    public function store(StoreProductRetailerRequest $request, Product $product): RedirectResponse
    {
        // $this->authorize('create', [ProductRetailer::class, $product]);

        $productRetailer = new ProductRetailer($request->all());
        $product->productRetailers()->save($productRetailer);

        $this->dispatch(new ProcessProductRetailer($productRetailer->id));

        return redirect()->route('products.show', [$product])->with('success', 'Product retailer added');
    }

    public function destroy(ProductRetailer $retailer): RedirectResponse
    {
        // $this->authorize('delete', $retailer);

        $retailer->delete();

        return redirect()->back()->with('success', 'Product retailer removed');
    }
}
