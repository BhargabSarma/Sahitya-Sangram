@extends('layouts.app')
@section('title', 'Your Cart')
@section('content')
    <div class="container">
        <h2>Your Cart</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($cartItems->isEmpty())
            <p>Your cart is empty.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->book?->title ?? 'Unknown' }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->book_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:50px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>
                                ₹{{ number_format($item->book?->digital_price ?? 0, 2) }}
                            </td>
                            <td>
                                ₹{{ number_format(($item->book?->digital_price ?? 0) * $item->quantity, 2) }}
                            </td>
                            <td>
                                <form action="{{ route('cart.remove', $item->book_id) }}" method="POST" class="d-inline">
                                    @csrf
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
                <!-- Checkout button creates order and sends to payment -->
                <a href="{{ route('order.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
            </div>
        @endif
    </div>
@endsection