@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>My Library</h2>

        @if ($books->isEmpty())
            <p>You have not purchased any books yet.</p>
        @else
            <div class="row">
                @foreach ($books as $book)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            @if($book->cover_image_front)
                                <img src="{{ asset($book->cover_image_front) }}" class="card-img-top" alt="{{ $book->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $book->title }}</h5>
                                <p class="card-text">{{ $book->subtitle ?? '' }}</p>
                                <a href="{{ route('books.read', $book->id) }}" class="btn btn-primary">Read Now</a>
                                @if(isset($reviews[$book->id]))
                                    <div class="mt-2">
                                        <strong>Your Review:</strong><br>
                                        <span>Rating: {{ $reviews[$book->id]->rating }}/5</span><br>
                                        <span>Comment: {{ $reviews[$book->id]->comment }}</span>
                                    </div>
                                @else
                                    <a href="{{ route('library.review.form', $book->id) }}" class="btn btn-success mt-2">Leave a
                                        Review</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection