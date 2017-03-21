@extends('layout')

@section('title', 'Create product')

@section('content')

    <div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
            <div class="form-group">
                <h2>Product name</h2>
                <input type="text" name="name" class="form-control input-lg" />
            </div>


            <input type="submit" value="Add" class="btn btn-primary" />

            {{ csrf_field() }}
        </form>
    </div>

@endsection