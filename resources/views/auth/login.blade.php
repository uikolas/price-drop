@extends('layout')

@section('title', 'Login')

@section('content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('login') }}" method="POST">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control input-lg @error('name') is-invalid @enderror" />
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <label>Password</label>
                    <input type="password" name="password" class="form-control input-lg @error('password') is-invalid @enderror" />
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                <input type="submit" value="Login" class="btn btn-primary" />

                @csrf
            </form>
        </div>
    </div>

@endsection
