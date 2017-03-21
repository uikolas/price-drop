@extends('layout')

@section('title', $product->name)

@section('content')

    <div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-block">
                <h1 class="card-title">{{ $product->name }}</h1>
                @if ($product->getBestProductRetailer())
                    <h4>Best retailer: <strong>{{ $product->getBestProductRetailer()->getRetailer()->name }}</strong></h4>
                    <h3>Best price: <strong>@money($product->getBestProductRetailer()->getPrice())</strong></h3>
                @endif
            </div>
        </div>

        <div class="pt-2">
            <div class="card">
                <div class="card-header">
                    Product retailers
                </div>
                <div class="card-block">
                    @if(count($product->productRetailers) >= 1)
                        <table class="table table-striped">
                            <thead class="thead-inverse">
                                <tr>
                                    <th style="width: 200px;">Retailer</th>
                                    <th>URL</th>
                                    <th>Price</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->productRetailers as $productRetailer)
                                    <tr>
                                        <td><strong>{{ $productRetailer->retailer->name }}</strong></td>
                                        <td><a href="{{ $productRetailer->url }}" target="_blank">{{ str_limit($productRetailer->url, 50) }}</a></td>
                                        <td>@money($productRetailer->getPrice())</td>
                                        <td>
                                            <form action="{{ route('product-retailers.destroy', [$productRetailer]) }}" method="POST">
                                                {{ method_field('DELETE') }}

                                                <button type="submit" id="delete-product-retailer" class="btn btn-link btn-sm" style="padding: 0;"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-danger">
                            No product retailers added
                        </div>
                    @endif

                    <a href="{{ route('product-retailers.create', $product) }}" class="btn btn-primary"> Add new product retailer</a>
                </div>
            </div>
        </div>

        <div class="pt-2">
            <form action="{{ route('products.destroy', [$product]) }}" method="POST">
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Product</button>

                {{ csrf_field() }}
            </form>
        </div>
    </div>

@endsection