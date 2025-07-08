{{-- filepath: resources/views/layouts/admin.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            <div class="me-auto">
                <a class="nav-link d-inline px-2 text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link d-inline px-2 text-white" href="{{ route('books.bookshelf') }}">Books</a>
                <a class="nav-link d-inline px-2 text-white" href="{{ route('authors') }}">Authors</a>
                <a class="nav-link d-inline px-2 text-white" href="{{ route('about') }}">About Us</a>
            </div>
            <span id="book-process-status" class="ms-3"></span>
            <div class="d-flex">
                @auth
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>

</body>

</html>