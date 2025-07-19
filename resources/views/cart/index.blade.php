@extends('layouts.app')
@section('title', 'Your Cart')
@section('content')
    <div class="container">
        <h2>Your Cart</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- For logged-in users --}}
        @if(isset($cart))
            @if($cart->items->isEmpty())
                <p>Your cart is empty.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart->items as $item)
                            <tr>
                                <td>{{ $item->book->title }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item->book_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $item->type }}">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:50px;">
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                                <td>{{ $item->type }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->book_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $item->type }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mb-3">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">Clear Cart</button>
                    </form>
                </div>
                <div>
                    <a href="{{ route('order.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                </div>
            @endif

            {{-- For guests --}}
        @elseif(isset($sessionCart))
            @if(empty($sessionCart))
                <p>Your cart is empty.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionCart as $item)
                            <tr>
                                <td>{{ $item['title'] }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item['book_id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $item['type'] }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            style="width:50px;">
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                                <td>{{ $item['type'] }}</td>
                                <td>₹{{ number_format($item['price'], 2) }}</td>
                                <td>₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['book_id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $item['type'] }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mb-3">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">Clear Cart</button>
                    </form>
                </div>
                <div>
                    <a href="{{ route('order.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                </div>
            @endif
        @endif
    </div>
@endsection