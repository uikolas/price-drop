<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRetailer\StoreProductRetailerRequest;
use App\Product;
use App\ProductRetailer;
use App\Retailer;
use Illuminate\Http\Request;

class ProductRetailerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Product $product)
    {
        $retailers = Retailer::all();

        return view('product-retailer.create', [
            'product'   => $product,
            'retailers' => $retailers
        ]);
    }

    /**
     * @param StoreProductRetailerRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRetailerRequest $request, Product $product)
    {
        $retailer = Retailer::findOrFail($request->get('retailer'));

        $productRetailer = new ProductRetailer($request->all());

        $productRetailer->product()->associate($product);
        $productRetailer->retailer()->associate($retailer);

        $productRetailer->save();

        return redirect()->route('products.show', [$product])->with('success', 'Product retailer added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductRetailer $productRetailer
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductRetailer $productRetailer)
    {
        $product = $productRetailer->getProduct();

        $productRetailer->delete();

        return redirect()->route('products.show', [$product])->with('success', 'Product retailer successful deleted');
    }
}
