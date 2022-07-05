@extends('layout')

@section('title', 'Product create')

@section('content')

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST">

                <div class="form-floating mb-3">
                    <input type="text" name="name" placeholder="Name" class="form-control input-lg @error('name') is-invalid @enderror" id="floatingInput" />
                    <label for="floatingInput">Product name</label>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <input type="submit" value="Create" class="btn btn-dark" />

                @csrf
            </form>
        </div>
    </div>
@endsection
