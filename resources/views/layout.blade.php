<html>
<head>
    <title>Price Drop - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
    <style>
        @media (min-width: 992px) {
            .rounded-lg-3 { border-radius: .5rem; }
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand me-md-auto" href="/"><i class="bi bi-droplet-fill"></i> Price drop</a>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item"><a href="{{ route('products.index') }}" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">Log out</a></li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
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

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                    <i class="bi bi-droplet-fill"></i>
                </a>
                <span class="mb-3 mb-md-0 text-body-secondary">{{ date('Y') }} Price drop</span>
            </div>
        </footer>
    </div>
</body>
</html>
