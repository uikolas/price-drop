@extends('layout')

@section('title', $product->name)

@section('content')

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
                        <h6>Best retailer: <a href="{{ $bestRetailer->url }}" target="_blank"><strong>{{ $bestRetailer->type->name }}</strong></a> with price: <strong>{{ $bestRetailer->price->getFormatted() }}</strong></h6>
                        <h6>Last update: {{ $bestRetailer->updated_at->diffForHumans() }}</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="pt-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Retailers</h2>

                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Retailer</th>
                        <th>Url</th>
                        <th>Price</th>
                        <th>Last price update</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productRetailers as $productRetailer)
                        <tr>
                            <td><span class="badge text-bg-secondary">{{ $productRetailer->type->name }}</span></td>
                            <td><a href="{{ $productRetailer->url }}" target="_blank">{{ Str::limit($productRetailer->url, 50) }}</a></td>
                            <td>{{ $productRetailer->price?->getFormatted() }}</td>
                            <td>{{ $productRetailer->price_updated_at?->diffForHumans() }}</td>
                            <td>
                                <form action="{{ route('trigger', [$productRetailer]) }}" method="POST">
                                    <button type="submit" value="t" class="btn btn-primary btn-sm" ><i class="bi bi-arrow-clockwise"></i></button>
                                    @csrf
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('retailers.destroy', [$productRetailer]) }}" method="POST">
                                    @method('DELETE')
                                    <button type="submit" value="Remove" class="btn btn-danger btn-sm" ><i class="bi bi-trash3"></i></button>
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $productRetailers->links() }}

                <div>
                    <a href="{{ route('products.retailers.create', [$product]) }}" class="btn btn-dark">Add new retailer</a>
                </div>
            </div>
        </div>
    </div>

    <div></div>


    <div class="pt-2 float-end">
        <form action="{{ route('products.destroy', [$product]) }}" method="POST">
            @method('DELETE')
            <input type="submit" value="Remove product" class="btn btn-danger btn-sm" />

            @csrf
        </form>
    </div>
@endsection
