<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment for Order #{{ $order->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <main class="container mx-auto max-w-md px-4 py-32">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle>
                    <path d="M8 12.5l2.5 2.5L16 9.5" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mb-2 text-center">Payment for Order #{{ $order->id }}</h1>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-700 mb-2">Order Items</h2>
                <ul class="divide-y divide-slate-200">
                    @php $calculatedTotal = 0; @endphp
                    @foreach($order->items as $item)
                        @php
                            $typeLabel = ucfirst(str_replace('_', ' ', $item->type));
                            $itemTotal = $item->price * $item->quantity;
                            $calculatedTotal += $itemTotal;
                        @endphp
                        <li class="py-2 flex justify-between items-center">
                            <span>
                                <strong>{{ $item->book->title }}</strong>
                                <span class="text-indigo-600">[{{ $typeLabel }}]</span>
                                <span class="text-slate-700">x{{ $item->quantity }}</span>
                            </span>
                            <span class="font-semibold">₹{{ number_format($itemTotal, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="text-lg text-slate-700 mb-6 text-center">
                <span class="font-semibold">Total:</span>
                <span class="text-indigo-700 font-bold text-xl">₹{{ number_format($calculatedTotal, 2) }}</span>
            </p>
            <form action="{{ route('payments.pay', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-lg shadow">
                    Pay Now
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('order.history') }}"
                    class="inline-block text-indigo-500 hover:text-indigo-600 text-sm font-semibold">View Order
                    History</a>
            </div>
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