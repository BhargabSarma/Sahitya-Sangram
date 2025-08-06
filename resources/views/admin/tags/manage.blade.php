@extends('layouts.admin')
@section('title', 'Manage Tags')
@section('content')
<div class="container">
    <h2>Manage Tags</h2>
    <!-- Add new tag -->
    <form action="{{ route('admin.tags.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="New Tag Name" required>
            <button class="btn btn-primary" type="submit">Add Tag</button>
        </div>
    </form>

    <!-- List tags and assign books -->
    @foreach($tags as $tag)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $tag->name }}</strong>
                <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" onsubmit="return confirm('Delete this tag?')" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tags.assignBooks', $tag->id) }}" method="POST">
                    @csrf
                    <label>Select up to 12 books for this tag:</label>
                    <div class="row">
                        @foreach($books as $book)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="books[]"
                                        value="{{ $book->id }}"
                                        {{ $tag->books->contains($book->id) ? 'checked' : '' }}
                                        @if(!$tag->books->contains($book->id) && $tag->books->count() >= 12) disabled @endif
                                    >
                                    <label class="form-check-label">{{ $book->title }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="btn btn-success mt-2" type="submit">Update Books</button>
                </form>
                <small class="text-muted">Selected: {{ $tag->books->count() }}/12</small>
            </div>
        </div>
    @endforeach
</div>
@endsection