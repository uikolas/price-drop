<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Price Drop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('css/index.css') }}" />
</head>
<body>

<div class="container mt-2">

    <p class="text-right">
        <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Register</a>
        <a class="btn btn-secondary btn-lg" href="{{ route('login') }}" role="button">Login</a>
    </p>

    <h1 class="display-1">
        <i class="fa fa-tint" aria-hidden="true"></i> Price drop
    </h1>

    <p class="lead">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur imperdiet malesuada vulputate. Etiam hendrerit lacus ut risus gravida, non dictum justo pellentesque. Donec rhoncus mi tincidunt felis posuere, vel ultrices dolor commodo. Vestibulum accumsan diam vitae aliquet laoreet. Praesent suscipit cursus elit eget consequat. Vivamus egestas justo ante, et rhoncus velit egestas nec. Quisque vitae convallis tellus. Donec sed elit at lorem tincidunt vulputate eget non mauris. Fusce eu nibh ac mi pellentesque imperdiet. Suspendisse tempus porta eros, sit amet ornare leo porta at. Suspendisse laoreet facilisis imperdiet. Nullam lobortis quam aliquam nibh tincidunt, pellentesque tempus sem fermentum. Morbi eu imperdiet lorem, eu mattis enim.
    </p>

    <div class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
                <img class="d-block img-fluid" src="{{ url('images/add-product-retailer.png') }}" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block img-fluid" src="{{ url('images/product.png') }}" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block img-fluid" src="{{ url('images/email-template.png') }}" alt="Third slide">
            </div>
        </div>
    </div>
</div>

</body>
</html>