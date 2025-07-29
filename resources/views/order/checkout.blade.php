<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
@include('components.header')

<main class="container mx-auto max-w-2xl px-4 py-24">
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
                            $type = $item->type ?? 'digital_copy';
                            $price = $item->price ?? 0; // Use the price from the cart item
                            $itemTotal = $price * $item->quantity;
                            $grandTotal += $itemTotal;
                        @endphp
                        <li class="flex justify-between py-2">
                            <span class="text-slate-700">
                                {{ $book->title ?? 'Unknown Book' }}
                                <span class="text-indigo-500 ms-2">[{{ ucfirst(str_replace('_', ' ', $type)) }}]</span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    
@include('components.footer')
</body>
</html>