<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-hero-shelf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <meta name="description" content="Your shopping cart at BookNest. View and manage your selected books.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
        <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- TailwindCSS (if you use Tailwind utility classes) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
</head>
<body>
@include('components.header')
<div class="card">
    <div class="row">
        <div class="col-md-8 cart">
            <div class="title">
                <div class="row">
                    <div class="col"><h4><b>Shopping Cart</b></h4></div>
                    <div class="col align-self-center text-right text-muted">{{ $count }} items</div>
                </div>
            </div>
            @forelse($cartItems as $item)
            <div class="row border-top border-bottom">
                <div class="row main align-items-center">
                    <div class="col-2">
                        <img class="img-fluid" src="{{ $item['cover_url'] }}" alt="{{ $item['title'] }}">
                    </div>
                    <div class="col">
                        <div class="row text-muted">{{ $item['author'] }}</div>
                        <div class="row">{{ $item['title'] }}</div>
                    </div>
                    <div class="col">
                        ₹{{ number_format($item['subtotal'], 2) }}
                        <div style="display: inline-flex; align-items: center; margin-left: 1rem;">
                            <form action="{{ route('cart.update', $item['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" name="action" value="decrease" style="background:none;border:none;">-</button>
                            </form>
                            <span class="border mx-1 px-2">{{ $item['qty'] }}</span>
                            <form action="{{ route('cart.update', $item['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" name="action" value="increase" style="background:none;border:none;">+</button>
                            </form>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="close" style="background:none;border:none;">&#10005;</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="row main align-items-center">
                <div class="col text-center text-muted py-5">Your cart is empty.</div>
            </div>
            @endforelse
            <div class="back-to-shop">
                <a href="{{ route('bookshelf') }}">&leftarrow;</a>
                <span class="text-muted">Back to shop</span>
            </div>
        </div>
        <div class="col-md-4 summary">
            <div><h5><b>Summary</b></h5></div>
            <hr>
            <div class="row">
                <div class="col" style="padding-left:0;">ITEMS {{ $count }}</div>
                <div class="col text-right">₹{{ number_format($total, 2) }}</div>
            </div>
            <form>
                <p>SHIPPING</p>
                <select><option class="text-muted">Standard-Delivery- ₹50.00</option></select>
                <p>GIVE CODE</p>
                <input id="code" placeholder="Enter your code">
            </form>
            <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                <div class="col">TOTAL PRICE</div>
                <div class="col text-right">₹{{ number_format($total + 50, 2) }}</div>
            </div>
            <button class="btn">CHECKOUT</button>
        </div>
    </div>
</div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- GSAP & ScrollTrigger for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>

    <!-- SplitType for animated text lines -->
    <script src="https://unpkg.com/split-type"></script>

    <!-- Your custom JS (should be after all libraries) -->
    <!-- If you have a separate hero parallax JS, include it here -->
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
</body>
</html>