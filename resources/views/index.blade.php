@extends('layout')

@section('title', 'Price drop')

@section('content')

    <div class="container my-5">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
            <div class="col-lg-5 p-3 p-lg-5 pt-lg-3">
                <h1 class="display-4 fw-bold lh-1 text-body-emphasis"><i class="bi bi-droplet-fill"></i> Price drop</h1>
                <p class="lead">Price drop is a tool, which allows keep track of product price drops get notification when price drops.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                    <a href="{{ route('login') }}" type="button" class="btn btn-outline-secondary btn-lg px-4">Get started</a>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1 p-0 overflow-hidden shadow-lg">
                <img class="rounded-lg-3" src="{{ asset('images/example.png') }}" alt="" width="720">
            </div>
        </div>
    </div>

@endsection
