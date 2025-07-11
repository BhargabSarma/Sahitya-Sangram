@extends('layouts.app')
@section('title', 'Order History')
@section('content')
    <div class="container">
        <h2>Your Orders</h2>
        @forelse($orders as $order)
            <div class="card mb-3">
                <div class="card-header">Order #{{ $order->id }} - ₹{{ number_format($order->total, 2) }} - Status:
                    {{ ucfirst($order->status) }}</div>
                <div class="card-body">
                    <ul>
                        @foreach($order->items as $item)
                            <li>{{ $item->book->title }} (x{{ $item->quantity }}) -
                                ₹{{ number_format($item->price * $item->quantity, 2) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @empty
            <p>You have no orders yet.</p>
        @endforelse
    </div>
@endsection