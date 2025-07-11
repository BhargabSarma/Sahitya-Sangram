@extends('layouts.app')
@section('title', 'Order Confirmation')
@section('content')
    <div class="container">
        <h2>Order Confirmation</h2>
        <div class="alert alert-success">Your order has been placed! Please proceed to payment.</div>
        <h4>Order #{{ $order->id }}</h4>
        <ul>
            @foreach($order->items as $item)
                <li>{{ $item->book->title }} (x{{ $item->quantity }}) - ₹{{ number_format($item->price * $item->quantity, 2) }}
                </li>
            @endforeach
        </ul>
        <p><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    </div>
@endsection