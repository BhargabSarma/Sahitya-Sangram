@extends('layouts.app')

@section('title', 'Authors')

@section('content')
    <div class="container mt-5">
        <h1>Authors</h1>
        <div class="row">
            @forelse($authors as $author)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($author->photo)
                            <img src="{{ asset('storage/' . $author->photo) }}" class="card-img-top" alt="{{ $author->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $author->name }}</h5>
                            <p class="card-text">{{ $author->bio }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p>No authors found.</p>
            @endforelse
        </div>
    </div>
@endsection