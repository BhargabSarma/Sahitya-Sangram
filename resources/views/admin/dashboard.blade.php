@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .dashboard-header h1 {
        color: #2c3e50;
        font-weight: 600;
    }

    .dashboard-header p {
        color: #718096;
    }

    .stat-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .stat-card .icon {
        font-size: 2.5rem;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 20px;
    }

    .stat-card .icon.icon-books {
        background-color: #eaf5ff;
        color: #3498db;
    }

    .stat-card .icon.icon-authors {
        background-color: #e8f6f3;
        color: #1abc9c;
    }
    
    .stat-card .icon.icon-users {
        background-color: #fef5e7;
        color: #f39c12;
    }

    .stat-card .info .number {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .stat-card .info .title {
        font-size: 1rem;
        color: #718096;
    }

    .management-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .management-card .card-header {
        background-color: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.25rem 1.5rem;
    }

    .management-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .table-responsive {
        border: none;
    }

    .table thead th {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .table .book-cover {
        width: 45px !important;
        height: 65px !important;
        object-fit: cover !important;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btns .btn {
        margin: 0 2px;
        padding: 0.3rem 0.6rem;
    }
</style>
@endpush

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <h1>Welcome, {{ Auth::user()->name }} 👋</h1>
        <p>Here's an overview of your bookstore's activity.</p>
    </div>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="icon icon-books">
                    <i class="fas fa-book"></i>
                </div>
                <div class="info">
                    {{-- To make this work, you must pass $bookCount from your controller --}}
                    <div class="number">{{ $bookCount ?? 0 }}</div>
                    <div class="title">Total Books</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="icon icon-authors">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div class="info">
                     {{-- To make this work, you must pass $authorCount from your controller --}}
                    <div class="number">{{ $authorCount ?? 0 }}</div>
                    <div class="title">Total Authors</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card">
                <div class="icon icon-users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info">
                     {{-- To make this work, you must pass $userCount from your controller --}}
                    <div class="number">{{ $userCount ?? 0 }}</div>
                    <div class="title">Total Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Books Management -->
    <div class="management-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Recently Added Books</h5>
            <div>
                <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Add Book
                </a>
                <a href="{{ route('authors.create') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-user-plus me-1"></i> Add Author
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td class="ps-3">
                                    @if($book->cover_image_front)
                                        <img 
                                            src="{{ asset('storage/'.$book->cover_image_front) }}" 
                                            alt="Cover" 
                                            class="book-cover"
                                            width="45"
                                            height="65"
                                            style="width:45px;height:65px;object-fit:cover;"/>
                                    @else
                                        <img 
                                            src="https://placehold.co/45x65/e2e8f0/adb5bd?text=N/A" 
                                            alt="No Cover" 
                                            class="book-cover"
                                            width="45"
                                            height="65"
                                            style="width:45px;height:65px;object-fit:cover;"/>
                                    @endif
                                </td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author->name ?? 'N/A' }}</td>
                                <td>${{ number_format($book->price ?? 0, 2) }}</td>
                              <td class="action-btns text-end pe-3">
                                    <a href="{{ route('books.read', ['id' => $book->id]) }}" class="btn btn-outline-primary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    
                                    {{-- Start Conversion button (only if eligible) --}}
                                    @if($book->book_file && $book->image_processing_status !== 'processing' && !$book->is_ready)
                                        <form action="{{ route('admin.books.startConversion', $book->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-outline-success btn-sm" title="Start Conversion">
                                                <i class="fas fa-sync"></i> Start Conversion
                                            </button>
                                        </form>
                                    @endif
                                
                                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <p class="mb-2">No books found.</p>
                                    <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">Add your first book</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection