@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review: {{ $book->title }}</h2>
    <form action="{{ route('library.review.store', $book->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif
</div>
@endsection