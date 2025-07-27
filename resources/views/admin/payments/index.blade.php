@extends('layouts.admin')
@section('content')
    <h2>Payments & Orders</h2>
    @foreach($payments as $payment)
        <div>
            Order #{{ $payment->order_id }} - Status: {{ $payment->status }}
            @if($payment->order && $payment->order->address)
                <div>
                    <strong>Address:</strong>
                    {{ $payment->order->address->name }},
                    {{ $payment->order->address->street_address }},
                    {{ $payment->order->address->city }},
                    {{ $payment->order->address->state }},
                    {{ $payment->order->address->zip }},
                    {{ $payment->order->address->country }}
                </div>
            @else
                <div><em>No address found</em></div>
            @endif
        </div>
    @endforeach
@endsection