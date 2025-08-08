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

            {{-- Default Courier Partner Section --}}
            @php $defaultCourier = \DB::table('default_courier_partner')->first(); @endphp
            @if($defaultCourier)
                <div class="mb-4">
                    <div class="alert alert-info">
                        <strong>Delivery Partner:</strong> {{ $defaultCourier->courier_name }}<br>
                        <strong>Shipping Price:</strong> ₹{{ number_format($defaultCourier->shipping_price, 2) }}
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="pincode" class="block font-semibold mb-1">Check Delivery Availability</label>
                <div class="flex gap-2">
                    <input type="text" id="pincode" maxlength="6" class="form-control w-40" placeholder="Enter Pincode">
                    <button type="button" id="checkPincodeBtn" class="btn btn-outline-primary">Check</button>
                </div>
                <div id="pincodeResult" class="mt-2 text-sm"></div>
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
                    <span>Subtotal</span>
                    <span id="orderSubtotal" data-subtotal="{{ $grandTotal }}">₹{{ number_format($grandTotal, 2) }}</span>
                </div>
                {{-- Default Courier Shipping --}}
                @if($defaultCourier)
                    {{-- Only show shipping if cart has hard_copy --}}
                    @php
                        $hasHardCopy = collect($cart->items)->contains(function($item) {
                            return $item->type === 'hard_copy';
                        });
                    @endphp

                    <div class="flex justify-between items-center font-semibold text-lg" id="shippingChargeRow" @if(!$hasHardCopy) style="display:none;" @endif>
                        <span>Shipping</span>
                        <span id="shippingCharge">
                            @if($defaultCourier && $hasHardCopy)
                                ₹{{ number_format($defaultCourier->shipping_price, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center font-bold text-lg border-t pt-4">
                        <span>Total</span>
                        <span id="orderTotal">
                            @if($defaultCourier && $hasHardCopy)
                                ₹{{ number_format($grandTotal + $defaultCourier->shipping_price, 2) }}
                            @else
                                ₹{{ number_format($grandTotal, 2) }}
                            @endif
                        </span>
                    </div>
                @else
                    <div class="flex justify-between items-center font-semibold text-lg" id="shippingChargeRow" style="display:none;">
                        <span>Shipping</span>
                        <span id="shippingCharge"></span>
                    </div>
                @endif
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
<script>
    // If you want pincode check to override shipping charge, keep this JS
    const hasHardCopy = {{ $hasHardCopy ? 'true' : 'false' }};

    document.getElementById('checkPincodeBtn').addEventListener('click', function() {
        const pincode = document.getElementById('pincode').value;
        const resultDiv = document.getElementById('pincodeResult');
        resultDiv.textContent = 'Checking...';

        fetch('{{ route('check.pincode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pincode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                resultDiv.textContent = data.error;
                document.getElementById('shippingChargeRow').style.display = 'none';
                document.getElementById('orderTotal').textContent = document.getElementById('orderSubtotal').dataset.subtotal;
            } else if (data.data && data.data.available_courier_companies && data.data.available_courier_companies.length) {
                resultDiv.innerHTML = '<span class="text-success">Delivery available!</span>';
                if (hasHardCopy) {
                    const defaultShipping = {{ $defaultCourier ? $defaultCourier->shipping_price : 0 }};
                    document.getElementById('shippingChargeRow').style.display = '';
                    document.getElementById('shippingCharge').textContent = '₹' + defaultShipping;
                    const subtotal = parseFloat(document.getElementById('orderSubtotal').dataset.subtotal);
                    document.getElementById('orderTotal').textContent = '₹' + (subtotal + parseFloat(defaultShipping)).toFixed(2);
                } else {
                    document.getElementById('shippingChargeRow').style.display = 'none';
                    document.getElementById('orderTotal').textContent = document.getElementById('orderSubtotal').dataset.subtotal;
                }
            } else {
                resultDiv.innerHTML = '<span class="text-danger">Delivery not available to this pincode.</span>';
                document.getElementById('shippingChargeRow').style.display = 'none';
                document.getElementById('orderTotal').textContent = document.getElementById('orderSubtotal').dataset.subtotal;
            }
        })
        .catch(() => {
            resultDiv.textContent = 'Error checking pincode.';
            document.getElementById('shippingChargeRow').style.display = 'none';
            document.getElementById('orderTotal').textContent = document.getElementById('orderSubtotal').dataset.subtotal;
        });
    });
</script>
@include('components.footer')
</body>
</html>