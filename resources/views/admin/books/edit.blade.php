@extends('layouts.admin')
@section('title', 'Edit Book')

@section('content')
    <div class="mb-4">
        <h2>Edit Book</h2>
    </div>
    <form method="POST" action="{{ route('admin.books.update', $book) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control" value="{{ old('title', $book->title) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Author ID</label>
            <input name="author_id" type="number" class="form-control" value="{{ old('author_id', $book->author_id) }}"
                required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $book->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image URL</label>
            <input name="cover_image" type="text" class="form-control" value="{{ old('cover_image', $book->cover_image) }}">
        </div>
        <div class="mb-3 form-check">
            <input name="is_live" type="checkbox" class="form-check-input" id="is_live" value="1" {{ old('is_live', $book->is_live) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_live">Is Live?</label>
        </div>
        <button class="btn btn-primary" type="submit">Update Book</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-link">Back</a>
    </form>
@endsection