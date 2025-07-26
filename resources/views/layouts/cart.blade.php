<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Book Retail')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Book Retail</a>
            <div class="me-auto">
                <a class="nav-link d-inline px-2" href="{{ route('index') }}">Home</a>
                <a class="nav-link d-inline px-2" href="{{ route('books.bookshelf') }}">Bookshelf</a>
                <a class="nav-link d-inline px-2" href="{{ route('authors') }}">Authors</a>
                <a class="nav-link d-inline px-2" href="{{ route('about') }}">About Us</a>
                @auth

                    <a class="nav-link d-inline px-2" href="{{ route('library.index') }}">Library</a>

                @endauth
            </div>
            <div class="d-flex">
                @auth

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">Logout</button>
                    </form>
                @else
                    <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>