<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- TailwindCSS (if you use Tailwind utility classes) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .cart-main-section {
            padding-top: 10rem;
            padding-bottom: 2rem;
        }
        @media (max-width: 576px) {
            .cart-main-section {
                padding-top: 8rem;
                padding-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
@include('components.header')

<main class="container mx-auto max-w-6xl px-50 cart-main-section">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="flex justify-between items-center border-b pb-6 mb-6">
            <h1 class="font-bold text-2xl md:text-3xl text-slate-800">Shopping Cart</h1>
            <h2 class="font-semibold text-slate-500">
                {{ method_exists($cartItems, 'count') ? $cartItems->count() : count($cartItems) }} Items
            </h2>
        </div>

        @if(method_exists($cartItems, 'isEmpty') ? $cartItems->isEmpty() : empty($cartItems))
            <div class="text-center py-12">
                <p class="text-slate-500 text-lg">Your cart is empty.</p>
            </div>
        @else
            <!-- Table headers for desktop, grid for mobile -->
            <div class="hidden md:flex mb-4 font-semibold text-slate-600 uppercase text-sm">
                <div class="w-2/5">Product Details</div>
                <div class="w-1/5 text-center">Quantity</div>
                <div class="w-1/5 text-center">Price</div>
                <div class="w-1/5 text-right">Total</div>
            </div>
            @php $grandTotal = 0; @endphp
            @foreach($cartItems as $item)
                @php
                    $title = $item->book?->title ?? 'Unknown';
                    $cover = $item->book?->cover_image_front ? asset('storage/' . $item->book->cover_image_front) : 'https://via.placeholder.com/100x150?text=No+Image';
                    $price = $item->book?->digital_price ?? 0;
                    $type = $item->type ?? 'digital';
                    $qty = $item->quantity ?? 1;
                    $subtotal = $price * $qty;
                    $grandTotal += $subtotal;
                @endphp
                <div class="flex flex-col md:flex-row items-center hover:bg-slate-50 -mx-4 px-4 py-4 border-b">
                    <!-- Product -->
                    <div class="w-full md:w-2/5 flex mb-4 md:mb-0">
                        <div class="w-24">
                            <img class="h-auto w-full rounded" src="{{ $cover }}" alt="{{ $title }}">
                        </div>
                        <div class="flex flex-col justify-between ml-4 flex-grow">
                            <div>
                                <span class="font-bold text-lg text-slate-800">{{ $title }}</span>
                                <span class="block text-indigo-500 text-sm font-semibold">{{ ucfirst($type) }}</span>
                            </div>
                            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="font-semibold hover:text-red-500 text-slate-500 text-xs">Remove</button>
                            </form>
                        </div>
                    </div>
                    <!-- Quantity Controls -->
                    <div class="w-full md:w-1/5 flex justify-center items-center mb-4 md:mb-0">
                        <form action="{{ route('cart.update', ['book' => $item->book_id]) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="quantity" value="{{ max(1, $qty - 1) }}">
                            <button type="submit" class="font-bold text-xl text-slate-500 hover:text-slate-700" @if($qty <= 1) disabled @endif>-</button>
                        </form>
                        <span class="mx-4 text-center w-8 font-semibold">{{ $qty }}</span>
                        <form action="{{ route('cart.update', ['book' => $item->book_id]) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="quantity" value="{{ $qty + 1 }}">
                            <button type="submit" class="font-bold text-xl text-slate-500 hover:text-slate-700">+</button>
                        </form>
                    </div>
                    <!-- Price -->
                    <div class="w-full md:w-1/5 text-center font-semibold text-base mb-4 md:mb-0">
                        ₹{{ number_format($price, 2) }}
                    </div>
                    <!-- Subtotal -->
                    <div class="w-full md:w-1/5 text-right font-bold text-lg text-slate-800">
                        ₹{{ number_format($subtotal, 2) }}
                    </div>
                </div>
            @endforeach

            <div class="flex justify-between items-center mt-8">
                <a href="{{ route('books.bookshelf') }}" class="font-semibold text-indigo-600 text-sm hover:text-indigo-500">
                    <svg class="inline w-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    Continue Shopping
                </a>
                <div class="text-right">
                    <div class="font-semibold text-lg">Total: <span class="font-bold text-slate-800">₹{{ number_format($grandTotal, 2) }}</span></div>
                    <a href="{{ route('order.checkout') }}">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg mt-4 transition-colors">
                            Checkout
                        </button>
                    </a>
                </div>
            </div>
            <div class="mt-8">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded transition-colors">
                        Clear Cart
                    </button>
                </form>
            </div>
        @endif
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
<!-- Your custom JS (should be after all libraries) -->
<!-- If you have a separate hero parallax JS, include it here -->
<script src="{{ asset('js/index.js') }}"></script>
<script src="{{ asset('js/hero-gsap.js') }}"></script>
@include('components.footer')
</body>
</html>