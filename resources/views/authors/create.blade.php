@extends('layouts.app')
@section('title', 'Add Author')
@section('content')
    <div class="container mt-4">
        <h2>Add Author</h2>
        <form method="POST" action="{{ route('authors.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Bio</label>
                <textarea name="bio" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <button class="btn btn-primary" type="submit">Add Author</button>
        </form>
    </div>
@endsection