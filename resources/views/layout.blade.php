<html>
<head>
    <title>Price Drop - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand me-md-auto" href="{{ route('products.index') }}"><i class="bi bi-droplet-fill"></i> Price drop</a>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item"><a href="{{ route('products.index') }}" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">Log out</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success pt-3 my-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="pt-2">
            @yield('content')
        </div>
    </div>
</body>
</html>
