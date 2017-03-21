@extends('layout')

@section('title', 'Products')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse($products as $product)
        <div class="pt-1">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">{{ $product->name }}</h4>
                    @if ($product->getBestProductRetailer())
                        <h6 class="card-subtitle mb-2 text-muted">Best price so far: @money($product->getBestProductRetailer()->getPrice())</h6>
                    @endif
                    <a href="{{ route('products.show', [$product]) }}" class="card-link">View product</a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-danger" role="alert">
            No products
        </div>
    @endforelse

    {{ $products->links() }}

@endsection