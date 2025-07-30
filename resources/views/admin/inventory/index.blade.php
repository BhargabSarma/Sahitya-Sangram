@extends('layouts.admin')

@section('content')
    <h1>Inventory Management</h1>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">Add Inventory</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Book</th>
                <th>Stock</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->book->title }}</td>
                    <td>{{ $inventory->stock }}</td>
                    <td>{{ $inventory->location }}</td>
                    <td>
                        <a href="{{ route('admin.inventory.edit', $inventory->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.inventory.destroy', $inventory->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this inventory?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection