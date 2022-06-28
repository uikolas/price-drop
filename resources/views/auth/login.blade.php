@extends('layout')

@section('title', 'Login')

@section('content')

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('login') }}" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" name="name" placeholder="Name" class="form-control input-lg @error('name') is-invalid @enderror" id="floatingInput" />
                    <label for="floatingInput">Name</label>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" placeholder="Password" class="form-control input-lg @error('password') is-invalid @enderror" id="floatingPassword" />
                    <label for="floatingPassword">Password</label>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <input type="submit" value="Login" class="btn btn-dark" />

                @csrf
            </form>
        </div>
    </div>

@endsection
