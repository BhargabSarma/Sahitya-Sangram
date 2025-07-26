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
                <h2 class="text-lg font-semibold text-slate-700 mb-4">Shipping Address</h2>
                <div class="grid gap-4">
                    <input type="text" name="address[line1]" class="border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Address Line 1" required>
                    <input type="text" name="address[city]" class="border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="City" required>
                    <input type="text" name="address[state]" class="border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="State" required>
                    <input type="text" name="address[zip]" class="border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Zip" required>
                    <input type="text" name="address[country]" class="border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Country" required>
                </div>
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