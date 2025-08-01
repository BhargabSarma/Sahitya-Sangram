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
    @if($payment->order)
        @if(!empty($payment->order->shiprocket_awb))
            <div>
                <strong>Shipment AWB:</strong> {{ $payment->order->shiprocket_awb }}
                <a href="https://shiprocket.co/tracking/{{ $payment->order->shiprocket_awb }}" target="_blank" rel="noopener"
                    class="btn btn-sm btn-outline-primary ms-2">Track</a>
            </div>
        @endif
        @if(!empty($payment->order->shiprocket_shipment_id))
            <div>
                <strong>Shipment ID:</strong> {{ $payment->order->shiprocket_shipment_id }}
            </div>
        @endif
    @endif
@endsection