<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(): View
    {
        $products = Auth::user()->products()->with('productRetailers')->simplePaginate(10);

        return view('products.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = new Product($request->all());

        Auth::user()->products()->save($product);

        return redirect()->route('products.show', [$product])->with('success', 'Product added');
    }

    public function show(Product $product): View
    {
        return view(
            'products.show',
            [
                'product' => $product,
                'bestRetailer' => $product->bestRetailer(),
                'productRetailers' => $product->productRetailers()->simplePaginate(10),
            ]
        );
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted');
    }
}
