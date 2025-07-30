@extends('layouts.admin')

@section('content')
    <h1>Add Inventory</h1>
    <form action="{{ route('admin.inventory.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="book_id">Book</label>
            <select name="book_id" id="book_id" class="form-control">
                @foreach($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control">
        </div>
        <button class="btn btn-primary">Add</button>
    </form>
@endsection