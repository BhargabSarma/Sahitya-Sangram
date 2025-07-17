<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f9fc;
        }

        .dashboard-header {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            color: #fff;
            padding: 30px 0 20px 0;
            border-radius: 0 0 24px 24px;
            margin-bottom: 32px;
        }

        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .add-btn {
            float: right;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">📚 Book Retail System</a>
            <div class="d-flex align-items-center">
                <span class="me-3 text-secondary">
                    <i class="bi bi-person-circle"></i> Hello, <strong>{{ Auth::user()->name }}</strong>
                </span>
                <span id="book-process-status" class="ms-3"></span>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="dashboard-header text-center">
        <h1 class="display-5 fw-bold">Welcome, {{ Auth::user()->name }} 👋</h1>
        <p class="lead mb-0">Manage your bookstore efficiently from the admin dashboard.</p>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5>
                            <span class="me-2">Your Role:</span>
                            <span class="badge bg-info text-dark">{{ ucfirst(Auth::user()->role) }}</span>
                            <a href="{{ route('authors.create') }}" class="btn btn-secondary btn-sm add-btn ms-2">
                                <i class="bi bi-person-plus"></i> Add Author
                            </a>
                            <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm add-btn">
                                <i class="bi bi-plus-circle"></i> Add Book
                            </a>
                        </h5>
                        <hr>

                        <!-- Dashboard content can be added here -->
                        <div class="row text-center py-4">
                            @forelse($books as $book)
                                <div class="col-md-3 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        @if($book->cover_image_front)
                                            <img src="{{ asset('storage/images/' . $book->cover_image_front) }}"
                                                class="card-img-top" alt="Cover" style="height: 220px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default_cover.jpg') }}" class="card-img-top"
                                                alt="No Cover" style="height: 220px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $book->title }}</h5>
                                            <a href="{{ route('books.read', ['id' => $book->id]) }}"
                                                class="btn btn-outline-primary btn-sm mt-2">View</a>
                                            <a href="{{ route('admin.books.edit', $book->id) }}"
                                                class="btn btn-outline-warning btn-sm mt-2 ms-1">Edit</a>
                                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm mt-2 ms-1"
                                                    onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p>No books found.</p>
                                </div>
                            @endforelse
                        </div>
                        <!-- More widgets/management links can be added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons CDN for icons (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.js"></script>
</body>

</html>
{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/Chart.min.css">
</head>

<body class="bg-gray-100 flex">
    @include('admin.components.sidebar')
    <div id="mainContent" class="flex-1 ml-64 p-8">
        <h1 class="text-2xl font-bold mb-8">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold mb-4">Total Sales</h2>
                <canvas id="totalSalesChart" height="180"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold mb-4">Sales Distribution</h2>
                <canvas id="salesPieChart" height="180"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold mb-4">Average Sales</h2>
                <canvas id="averageSalesChart" height="180"></canvas>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="mt-8 inline-block text-blue-600 hover:underline">Go to
            Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>

</html> --}}