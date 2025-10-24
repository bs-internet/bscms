<!doctype html>
<html lang="tr-TR" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BS CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    @yield('css')
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    @include('admin.template.header')
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <aside class="col-md-3 col-lg-2 col-xl-2 border-end bg-white p-0">
                @include('admin.template.sidebar')
            </aside>
            <main class="col-md-9 col-lg-10 col-xl-10 p-4">
                @include('admin.template.flash')
                @yield('content')
            </main>
        </div>
    </div>
    @include('admin.template.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    @yield('js')
</body>
</html>
