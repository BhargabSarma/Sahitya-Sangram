@extends('layouts.admin')
@section('title', 'All Orders')
@section('content')
    <div class="container py-4">
        <h2 class="mb-3">All Orders</h2>
        @if($orders->isEmpty())
            <div class="alert alert-info">No orders found.</div>
        @else
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Shipment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @if($order->user)
                                    {{ $order->user->name }}<br>
                                    <small>{{ $order->user->email }}</small>
                                @else
                                    <em>Unknown</em>
                                @endif
                            </td>
                            <td>₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($order->shiprocket_awb)
                                    <span class="badge bg-info text-dark">AWB: {{ $order->shiprocket_awb }}</span>
                                @else
                                    <span class="text-warning">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection