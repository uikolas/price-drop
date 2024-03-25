@extends('layout')

@section('title', 'Products')

@section('content')

    @foreach($products as $product)
        @php
            $bestRetailer = $product->bestRetailer();
        @endphp
        <div class="pt-1">
            <div class="card shadow-sm mb-3">
                <div class="row g-0">
                    <div class="col-md-4" style="max-width: 140px;">
                        @isset($bestRetailer->image)
                            <img src="{{ $bestRetailer->image }}" class="img-fluid rounded-start" alt="product image" />
                        @endisset
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h2 class="card-title">{{ $product->name }}</h2>
                            @if ($bestRetailer)
                                <h6 class="card-subtitle mb-2 text-muted">Best price so far: <strong>{{ $bestRetailer->price->getFormatted() }}</strong></h6>
                                <h6>Last update: {{ $bestRetailer->updated_at->diffForHumans() }}</h6>
                            @endif
                            <a href="{{ route('products.show', [$product]) }}" class="card-link">View product</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="my-3">
        <a href="{{ route('products.create') }}" class="btn btn-dark">Create new product</a>
    </div>

    {{ $products->links() }}
@endsection
