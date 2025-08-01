<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,600,700,800&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Inter', Arial, sans-serif;
        }

        .container {
            max-width: 800px;
        }

        .card {
            border-radius: 10px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .order-item {
            background: #fff;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #eee;
        }

        .order-shipping {
            line-height: 1.8;
        }

        .shiprocket-status {
            margin-top: 8px;
        }

        .shiprocket-awb {
            font-size: 0.95em;
        }

        .shiprocket-track {
            font-size: 0.93em;
        }
    </style>
</head>

<body>
    @include('components.header')

    <div class="container py-24">
        <h2 class="mb-4 fw-bold">Your Orders</h2>

        @if($orders->count() == 0)
            <div class="alert alert-info">You have not placed any orders yet.</div>
        @else
            @foreach($orders as $order)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">Order #{{ $order->id }}</span>
                            <span class="badge bg-secondary ms-2">{{ $order->status ?? 'Pending' }}</span>
                        </div>
                        <div class="text-end text-muted small">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div><strong>{{ $item->book->title ?? 'Book' }}</strong></div>
                                <div class="small text-muted mb-1">Qty: {{ $item->quantity }}</div>
                                <div>{{ number_format($item->price, 2) }} ₹</div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-primary">{{ number_format($order->total, 2) }} ₹</span>
                        </div>

                        {{-- Shiprocket status section --}}
                        @if(!empty($order->shiprocket_awb) || !empty($order->shiprocket_shipment_id))
                            <div class="shiprocket-status mt-3">
                                <div>
                                    <span class="fw-bold text-success"><i class="fa fa-truck"></i> Shipment Info</span>
                                </div>
                                @if(!empty($order->shiprocket_awb))
                                    <div class="shiprocket-awb">
                                        <span class="badge bg-info text-dark">AWB: {{ $order->shiprocket_awb }}</span>
                                    </div>
                                @endif
                                @if(!empty($order->shiprocket_shipment_id))
                                    <div class="shiprocket-awb">
                                        Shipment ID: {{ $order->shiprocket_shipment_id }}
                                    </div>
                                @endif
                                {{-- Shiprocket tracking link --}}
                                <div class="shiprocket-track mt-2">
                                    <a href="https://shiprocket.co/tracking/{{ $order->shiprocket_awb }}" target="_blank"
                                        rel="noopener" class="btn btn-sm btn-outline-primary">
                                        Track Shipment
                                    </a>
                                </div>
                            </div>
                        @elseif($order->status == 'pending')
                            <div class="shiprocket-status mt-3 text-warning">
                                <i class="fa fa-clock-o"></i> Awaiting shipment creation
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light order-shipping">
                        @php
                            $shipping = is_array($order->shipping_address)
                                ? $order->shipping_address
                                : json_decode($order->shipping_address, true);
                        @endphp
                        <span class="small text-muted">
                            Shipping to:
                            @if(!empty($shipping['name'])) <b>{{ $shipping['name'] }}</b> @endif
                            @if(!empty($shipping['phone'])) <span>({{ $shipping['phone'] }})</span> @endif
                            @php
                                $fields = [];
                                foreach (['street', 'city', 'state', 'postal_code', 'country'] as $field) {
                                    if (!empty($shipping[$field])) {
                                        $fields[] = $shipping[$field];
                                    }
                                }
                            @endphp
                            @if(count($fields))
                                <span>
                                    @if(!empty($fields)) — {!! implode(', ', array_map('e', $fields)) !!} @endif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>

    @include('components.footer')
</body>

</html>