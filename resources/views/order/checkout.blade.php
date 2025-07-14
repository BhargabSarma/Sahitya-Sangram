@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <div class="container">
        <h2>Checkout</h2>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($cart->items->isEmpty())
            <p>Your cart is empty. <a href="{{ route('cart.index') }}">Go back to cart</a></p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                        <tr>
                            <td>{{ $item->book->title }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->book->digital_price, 2) }}</td>
                            <td>₹{{ number_format($item->book->digital_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h4 class="mt-3">Grand Total:
                ₹{{ number_format($cart->items->sum(fn($item) => $item->book->digital_price * $item->quantity), 2) }}</h4>

            <form action="{{ route('order.place') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Place Order & Pay</button>
            </form>
            <div class="mt-3">
                <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
            </div>
        @endif
    </div>
@endsection