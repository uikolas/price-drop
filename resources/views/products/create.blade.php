@extends('layout')

@section('title', 'Product create')

@section('content')

{{--    @if ($errors->any())--}}
{{--        <div class="alert alert-danger">--}}
{{--            <ul>--}}
{{--                @foreach ($errors->all() as $error)--}}
{{--                    <li>{{ $error }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">

                <div class="mb-3">
                    <label>Product name</label>
                    <input type="text" name="name" class="form-control input-lg @error('name') is-invalid @enderror" />
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                <input type="submit" value="Add" class="btn btn-primary" />

                @csrf
            </form>
        </div>
    </div>
@endsection
