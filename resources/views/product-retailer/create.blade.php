@extends('layout')

@section('title', 'Create product retailer')

@section('content')

    <div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('product-retailers.store', [$product]) }}" method="POST">
            <div class="form-group">
                <h2>URL from shop</h2>
                <input type="text" name="url" class="form-control input-lg" value="{{ old('url') }}" />
            </div>

            <div class="form-group">
                <h3>Shop</h3>
                <select name="retailer" id="retailer" class="form-control">
                    @foreach($retailers as $retailer)
                        <option value="{{ $retailer->id }}">{{ $retailer->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="submit" value="Add" class="btn btn-primary" />

            {{ csrf_field() }}
        </form>
    </div>

@endsection