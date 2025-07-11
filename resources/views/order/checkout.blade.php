@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <div class="container">
        <h2>Checkout</h2>
        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <h4>Shipping Address</h4>
            <div class="mb-3"><input type="text" name="address[line1]" class="form-control" placeholder="Address Line 1"
                    required></div>
            <div class="mb-3"><input type="text" name="address[city]" class="form-control" placeholder="City" required>
            </div>
            <div class="mb-3"><input type="text" name="address[state]" class="form-control" placeholder="State" required>
            </div>
            <div class="mb-3"><input type="text" name="address[zip]" class="form-control" placeholder="Zip" required></div>
            <div class="mb-3"><input type="text" name="address[country]" class="form-control" placeholder="Country"
                    required></div>

            <h4>Order Summary</h4>
            <ul>
                @foreach($cart as $item)
                    <li>{{ $item['title'] }} (x{{ $item['quantity'] }}) -
                        ₹{{ number_format($item['price'] * $item['quantity'], 2) }}</li>
                @endforeach
            </ul>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Place Order</button>
            </div>
        </form>
    </div>
@endsection