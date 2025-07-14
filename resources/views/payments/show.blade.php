@extends('layouts.app')
@section('title', 'Payment')
@section('content')
    <div class="container">
        <h2>Payment for Order #{{ $order->id }}</h2>
        <p><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
        <form action="{{ route('payments.pay', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Pay Now</button>
        </form>
    </div>
@endsection