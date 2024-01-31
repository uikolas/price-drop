@extends('layout')

@section('title', 'Product create')

@section('content')

{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                <ul>--}}
{{--                    @foreach ($errors->all() as $error)--}}
{{--                        <li>{{ $error }}</li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        @endif--}}

    <div class="card shadow-sm">
        <div class="card-body">

            <livewire:create-product-retailer :product="$product" :types="$types" />

{{--            <form action="{{ route('products.retailers.store', [$product]) }}" method="POST">--}}

{{--                <livewire:select-retailer :types="$types" />--}}

{{--                <div class="form-floating mb-3">--}}
{{--                    <input type="text" name="url" placeholder="Url" class="form-control input-lg @error('url') is-invalid @enderror" id="floatingInput" />--}}
{{--                    <label for="floatingInput">Url</label>--}}
{{--                    @error('url')--}}
{{--                    <div class="invalid-feedback">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                </div>--}}

{{--                <div class="form-floating mb-3">--}}
{{--                    <select class="form-select @error('type') is-invalid @enderror" name="type" aria-label="Floating label select example" id="floatingSelect">--}}
{{--                        @foreach($types as $type)--}}
{{--                            <option value="{{ $type->value }}">{{ $type->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <label for="floatingSelect">Retailer</label>--}}
{{--                    @error('type')--}}
{{--                    <div class="invalid-feedback">--}}
{{--                        {{ $message }}--}}
{{--                    </div>--}}
{{--                    @enderror--}}
{{--                </div>--}}

{{--                <input type="submit" value="Create" class="btn btn-dark" />--}}

{{--                @csrf--}}
{{--            </form>--}}
        </div>
    </div>
@endsection
