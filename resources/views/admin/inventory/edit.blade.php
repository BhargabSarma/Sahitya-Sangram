@extends('layouts.admin')

@section('content')
    <h1>Edit Inventory</h1>
    <form action="{{ route('admin.inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="book_id">Book</label>
            <select name="book_id" id="book_id" class="form-control">
                @foreach($books as $book)
                    <option value="{{ $book->id }}" @if($book->id == $inventory->book_id) selected @endif>{{ $book->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ $inventory->stock }}" min="0"
                required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ $inventory->location }}">
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
@endsection