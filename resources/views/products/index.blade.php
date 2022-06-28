@extends('layout')

@section('title', 'Products')

@section('content')

    @foreach($products as $product)
        <div class="pt-1">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $product->name }}</h4>
                    @php
                        $bestRetailer = $product->bestRetailer();
                    @endphp
                    @if ($bestRetailer)
                        <h6 class="card-subtitle mb-2 text-muted">Best price so far: <strong>{{ $bestRetailer->price }}</strong></h6>
                    @endif
                    <a href="{{ route('products.show', [$product]) }}" class="card-link">View product</a>
                </div>
            </div>
        </div>
    @endforeach

    <div class="my-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Create new product</a>
    </div>

    {{ $products->links() }}
@endsection
