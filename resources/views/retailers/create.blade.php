@extends('layout')

@section('title', 'Product create')

@section('content')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <div>
        <form action="{{ route('products.retailers.store', [$product]) }}" method="POST">

            <div class="mb-3">
                <label>Url</label>
                <input type="text" name="url" class="form-control input-lg @error('url') is-invalid @enderror" />
                @error('url')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Type</label>
                <select class="form-select @error('type') is-invalid @enderror" name="type">
                    @foreach($types as $type)
                        <option value="{{ $type->value }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <input type="submit" value="Add" class="btn btn-primary" />

            @csrf
        </form>
    </div>
@endsection
