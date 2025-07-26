<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TailwindCSS for modern utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
@include('components.header')

<main class="container mx-auto max-w-2xl px-4 py-12">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
        <h1 class="font-bold text-2xl md:text-3xl text-slate-800 mb-8">Checkout</h1>
        <form action="{{ route('order.place') }}" method="POST" class="space-y-8">
            @csrf

            <div>
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Delivery Address</h2>
                @if($addresses->count())
                    <div class="space-y-3">
                        @foreach($addresses as $address)
                            <label class="flex items-start gap-3 border rounded-lg p-3 cursor-pointer @if($address->is_default) border-indigo-500 @endif">
                                <input
                                    type="radio"
                                    name="address_id"
                                    value="{{ $address->id }}"
                                    @if(($defaultAddress && $address->id == $defaultAddress->id) || (!$defaultAddress && $loop->first)) checked @endif
                                    class="mt-1 accent-indigo-600"
                                    required
                                >
                                <span>
                                    <span class="font-semibold">{{ $address->name }}</span>
                                    <span class="badge bg-light text-dark ms-2">{{ $address->type }}</span><br>
                                    <span class="text-sm text-slate-600">{{ $address->full_name }}</span><br>
                                    <span class="text-sm text-slate-600">{{ $address->street_address }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip }}<br>{{ $address->country }}<br>Phone: {{ $address->phone }}</span>
                                    @if($address->is_default)
                                        <span class="badge bg-primary ms-2">Default</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted mb-2">No address found. Please <a href="{{ route('profile.show') }}" class="text-indigo-600">add an address</a> in your profile.</div>
                @endif
            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Order Summary</h2>
                <ul class="divide-y divide-slate-200 mb-4">
                    @php $grandTotal = 0; @endphp
                    @foreach($cart->items as $item)
                        @php
                            $book = $item->book;
                            $itemTotal = $book->digital_price * $item->quantity;
                            $grandTotal += $itemTotal;
                        @endphp
                        <li class="flex justify-between py-2">
                            <span class="text-slate-700">
                                {{ $book->title ?? 'Unknown Book' }}
                                <span class="text-slate-400">(x{{ $item->quantity }})</span>
                            </span>
                            <span class="font-semibold text-slate-800">₹{{ number_format($itemTotal, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="flex justify-between items-center font-semibold text-lg border-t pt-4">
                    <span>Total</span>
                    <span class="font-bold text-slate-800">₹{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">Place Order</button>
            </div>
        </form>
    </div>
</main>

@include('components.footer')
</body>
</html>