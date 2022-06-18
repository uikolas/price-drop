@extends('layout')

@section('title', $product->name)

@section('content')

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $product->name }}</h2>
            @if ($bestRetailer)
                <h5>Best retailer: <a href="{{ $bestRetailer->url }}"><strong>{{ $bestRetailer->type->name }}</strong></a></h5>
                <h5>Best price: <strong>{{ $bestRetailer->price }}</strong></h5>
            @endif
        </div>
    </div>

    <div class="pt-2">
        <div class="card">
            <div class="card-header bg-transparent">Product retailers</div>

            <div class="card-body">
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
                            <td><a href="{{ $productRetailer->url }}">{{ Str::limit($productRetailer->url, 50) }}</a></td>
                            <td>{{ $productRetailer->price }}</td>
                            <td>{{ $productRetailer->updated_at }}</td>
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
                    <a href="{{ route('products.retailers.create', [$product]) }}" class="btn btn-primary">Add new retailer</a>
                </div>
            </div>
        </div>
    </div>

    <div></div>


    <div class="pt-2">
        <form action="{{ route('products.destroy', [$product]) }}" method="POST">
            @method('DELETE')
            <input type="submit" value="Remove product" class="btn btn-danger btn-sm" />

            @csrf
        </form>
    </div>
@endsection
