<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment for Order #{{ $order->id }}</title>
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

<main class="container mx-auto max-w-md px-4 py-12">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle>
                <path d="M8 12.5l2.5 2.5L16 9.5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-2 text-center">Payment for Order #{{ $order->id }}</h1>
        <p class="text-lg text-slate-700 mb-6 text-center">
            <span class="font-semibold">Total:</span>
            <span class="text-indigo-700 font-bold text-xl">₹{{ number_format($order->total, 2) }}</span>
        </p>
        <form action="{{ route('payments.pay', $order->id) }}" method="POST" class="space-y-4">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-lg shadow">
                Pay Now
            </button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('order.history') }}" class="inline-block text-indigo-500 hover:text-indigo-600 text-sm font-semibold">View Order History</a>
        </div>
    </div>
</main>

@include('components.footer')
</body>
</html>