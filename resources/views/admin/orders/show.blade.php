@extends('layouts.admin')
@section('title', 'Order Details')
@section('content')
    <div class="container py-4">
        <h2 class="mb-3">Order #{{ $order->id }}</h2>

        <div class="mb-4">
            <strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
            <span class="ms-3 text-muted small">{{ $order->created_at->format('d M Y, h:i A') }}</span>
        </div>

        <div class="mb-4">
            <strong>User:</strong>
            @if($order->user)
                {{ $order->user->name }} ({{ $order->user->email }})
            @else
                <em>Unknown</em>
            @endif
        </div>

        <div class="mb-4">
            <strong>Shipping Address:</strong><br>
            @php
                $shipping = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
            @endphp
            @if($shipping)
                <ul>
                    <li>Name: {{ $shipping['name'] ?? '' }}</li>
                    <li>Phone: {{ $shipping['phone'] ?? '' }}</li>
                    <li>Address: {{ $shipping['street'] ?? '' }}, {{ $shipping['city'] ?? '' }}, {{ $shipping['state'] ?? '' }},
                        {{ $shipping['postal_code'] ?? '' }}, {{ $shipping['country'] ?? '' }}</li>
                </ul>
            @else
                <em>No address info</em>
            @endif
        </div>

        <div class="mb-4">
            <strong>Items:</strong>
            <ul>
                @foreach($order->items as $item)
                    <li>
                        {{ $item->book->title ?? 'Book' }} -
                        Qty: {{ $item->quantity }},
                        Type: {{ ucfirst($item->type) }},
                        Price: ₹{{ number_format($item->price, 2) }}
                    </li>
                @endforeach
            </ul>
            <strong>Total:</strong> ₹{{ number_format($order->total, 2) }}
        </div>

        <div class="mb-4">
            <strong>Shiprocket Shipment:</strong><br>
            @if(!empty($order->shiprocket_awb) || !empty($order->shiprocket_shipment_id))
                <div>
                    @if(!empty($order->shiprocket_awb))
                        <span class="badge bg-info text-dark">AWB: {{ $order->shiprocket_awb }}</span>
                    @endif
                    @if(!empty($order->shiprocket_shipment_id))
                        <span>Shipment ID: {{ $order->shiprocket_shipment_id }}</span>
                    @endif
                    <div class="mt-2">
                        <a href="https://shiprocket.co/tracking/{{ $order->shiprocket_awb }}" target="_blank" rel="noopener"
                            class="btn btn-sm btn-outline-primary">
                            Track Shipment
                        </a>
                    </div>
                </div>
            @else
                <span class="text-warning"><i class="fa fa-clock-o"></i> Shipment not created yet</span>
            @endif
            @if(!empty($order->shiprocket_response))
                <div class="mt-2">
                    <details>
                        <summary class="small text-muted">Raw Shiprocket API Response</summary>
                        <pre>{{ json_encode($order->shiprocket_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </details>
                </div>
            @endif
        </div>
    </div>
@endsection