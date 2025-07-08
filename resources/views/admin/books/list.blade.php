@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author ID</th>
            <th>Description</th>
            <th>Cover Image</th>
            <th>Live?</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author_id }}</td>
                <td>{{ $book->description }}</td>
                <td>
                    @if($book->cover_image)
                        <img src="{{ $book->cover_image }}" alt="Cover" width="60">
                    @endif
                </td>
                <td>{{ $book->is_live ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete this book?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No books found.</td>
            </tr>
        @endforelse
    </tbody>
</table>