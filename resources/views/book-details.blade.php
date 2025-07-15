<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $book->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book-details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,600,700,800&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    @include('components.header')
    <div class="max-w-7xl mx-auto px-4 py-32">
        <div class="flex flex-col md:flex-row gap-10 bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <!-- Book Cover with Animated Hover Effect & Flip -->
            <div class="flex-shrink-0 flex flex-col items-center md:items-start">
                <div class="rounded-xl overflow-hidden shadow-lg bg-gray-100 mb-6 book-cover-hover"
                    style="max-width: 240px;">
                    <div class="shelf-books__item">
                        <div class="flip-container" id="book-flip">
                            <div class="flipper">
                                <!-- Front side (animated hover) -->
                                <div class="front shelf-books__container">
                                    <div class="shelf-books__cover">
                                        <div class="shelf-books__back-cover"></div>
                                        <div class="shelf-books__inside">
                                            <div class="shelf-books__page"></div>
                                            <div class="shelf-books__page"></div>
                                            <div class="shelf-books__page"></div>
                                        </div>
                                        <div class="shelf-books__image">
                                            <img src="{{ asset('storage/' . $book->cover_image_front) }}"
                                                alt="{{ $book->title }} Front Cover">
                                            <div class="shelf-books__effect"></div>
                                            <div class="shelf-books__light"></div>
                                        </div>
                                        <div class="shelf-books__hitbox"></div>
                                    </div>
                                </div>
                                <!-- Back side (flip on click) -->
                                <div class="back shelf-books__container flex items-center justify-center"
                                    style="background: #23243a;">
                                    <div class="shelf-books__image">
                                        <img src="{{ asset('storage/' . $book->cover_image_back) }}"
                                            alt="{{ $book->title }} Back Cover">
                                        <div class="shelf-books__effect"></div>
                                        <div class="shelf-books__light"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Read Now Button -->
                <a href="{{ route('books.read', ['id' => $book->id]) }}"
                    class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition mb-4 block text-center">
                    Read Now
                </a>
                <!-- Read Sample Button triggers overlay -->
                <button onclick="openSampleOverlay()"
                    class="px-6 py-2 rounded-lg border-2 border-indigo-600 text-indigo-600 font-semibold hover:bg-indigo-50 transition mb-4 block text-center">
                    Read Sample
                </button>
                <div class="flex flex-col gap-2 w-full">
                    <button
                        class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Hard Copy ₹{{ $book->hard_copy_price ?? '1690' }}
                        <span class="line-through text-gray-400 text-sm ml-2">2990</span>
                    </button>
                    <a href="{{ route('order.checkout') }}"
                        class="w-full px-4 py-2 rounded-lg bg-indigo-100 text-indigo-700 font-semibold hover:bg-indigo-200 transition text-center block">
                        Digital Copy ₹{{ $book->digital_price ?? '990' }}
                        <span class="line-through text-gray-400 text-sm ml-2">1690</span>
                    </a>
                    <button id="add-to-cart-btn"
                        class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition mt-4"
                        data-book-id="{{ $book->id }}" type="button">
                        Add to Cart
                    </button>
                </div>
            </div>
            <!-- Book Details -->
            <div class="flex-1 flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">Books</span>
                    <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                    <span class="text-xs text-pink-600 font-semibold uppercase tracking-wide">New Launch</span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">{{ $book->title }}</h1>
                <div class="text-lg text-gray-500 mb-4">{{ $book->subtitle ?? '' }}</div>
                <!-- Author name only -->
                <div class="mb-2 text-sm text-indigo-600 font-semibold uppercase tracking-wide">
                    By {{ strtoupper($book->author->name ?? 'AUTHOR') }}
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-400 text-xl">
                        @for ($i = 0; $i < floor($book->rating ?? 4.5); $i++)
                            ★
                        @endfor
                        @if (isset($book->rating) && $book->rating - floor($book->rating) >= 0.5)
                            ☆
                        @endif
                    </span>
                    <span class="text-gray-700 text-sm">
                        {{ $book->rating ?? '4.5' }} |
                        <a href="#" class="text-indigo-600 underline">
                            {{ $book->review_count ?? 5 }} Reviews
                        </a>
                    </span>
                </div>
                <div class="flex flex-wrap gap-2 mb-8">
                    @php
                        $pagesPerDay = $book->number_of_pages && $book->number_of_pages > 0 ? ceil($book->number_of_pages / 30) : 0;
                    @endphp
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">
                        30 days - {{ $pagesPerDay }} pages per day
                    </span>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $book->language ?? 'English' }}
                    </span>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $book->number_of_pages ?? '439' }}
                    </span>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $book->level ?? 'Intermediate' }}
                    </span>
                </div>
                <section>
                    <h2 class="text-indigo-600 font-bold text-base mb-2 tracking-wide">DESCRIPTION</h2>
                    <p class="text-gray-700 text-base leading-relaxed">
                        {{ $book->description ?? '' }}
                    </p>
                </section>
            </div>
        </div>
    </div>
    <!-- Sample Reader Overlay (hidden by default) -->
    @include('components.read-sample-overlay', ['book' => $book, 'samplePages' => $samplePages])
    <!-- JS & GSAP for animated hover and flip -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script>
        // GSAP shelf book hover animation
        document.addEventListener("DOMContentLoaded", function () {
            gsap.registerPlugin(ScrollTrigger, CustomEase);
            CustomEase.create("bookEase", "0.25, 1, 0.5, 1");
            // Animate shelf book hover on the front cover only
            const bookItem = document.querySelector('.shelf-books__item');
            if (bookItem) {
                const hitbox = bookItem.querySelector(".shelf-books__hitbox");
                const bookImage = bookItem.querySelector(".shelf-books__image");
                const bookEffect = bookItem.querySelector(".shelf-books__effect");
                const pages = bookItem.querySelectorAll(".shelf-books__page");
                const bookLight = bookItem.querySelector(".shelf-books__light");
                gsap.set(bookImage, {
                    boxShadow:
                        "0 10px 20px rgba(0,0,0,0.15), 0 30px 45px rgba(0,0,0,0.12), 0 60px 80px rgba(0,0,0,0.1)"
                });
                gsap.set(bookLight, { opacity: 0.1, rotateY: 0 });
                gsap.set(pages, { x: 0 });
                const hoverIn = gsap.timeline({ paused: true });
                hoverIn.to(
                    bookImage,
                    {
                        duration: 0.7,
                        rotateY: -12,
                        translateX: -6,
                        scaleX: 0.96,
                        boxShadow:
                            "10px 10px 20px rgba(0,0,0,0.25), 20px 20px 40px rgba(0,0,0,0.2), 40px 40px 60px rgba(0,0,0,0.15)",
                        ease: "bookEase"
                    },
                    0
                );
                hoverIn.to(
                    bookEffect,
                    {
                        duration: 0.7,
                        marginLeft: 10,
                        ease: "bookEase"
                    },
                    0
                );
                hoverIn.to(
                    bookLight,
                    {
                        duration: 0.7,
                        opacity: 0.2,
                        rotateY: -12,
                        ease: "bookEase"
                    },
                    0
                );
                if (pages.length) {
                    hoverIn.to(
                        pages[0],
                        { x: "4px", duration: 0.7, ease: "power1.inOut" },
                        0
                    );
                    hoverIn.to(
                        pages[1],
                        { x: "2px", duration: 0.7, ease: "power1.inOut" },
                        0
                    );
                    hoverIn.to(
                        pages[2],
                        { x: "0px", duration: 0.7, ease: "power1.inOut" },
                        0
                    );
                }
                if (hitbox) {
                    hitbox.addEventListener("mouseenter", () => hoverIn.play());
                    hitbox.addEventListener("mouseleave", () => hoverIn.reverse());
                }
            }

            // Flip animation for front/back cover
            const flipContainer = document.getElementById('book-flip');
            if (flipContainer) {
                flipContainer.addEventListener('click', function () {
                    flipContainer.classList.toggle('flipped');
                });
            }
        });
    </script>
    <script>
        document.getElementById('add-to-cart-btn').addEventListener('click', function () {
            const bookId = this.getAttribute('data-book-id');
            fetch('{{ url('/cart/add') }}/' + bookId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.cart_count;
                });
        });

        // Sample Reader Overlay JS
        // jQuery and Turn.js
        // (if not loaded above, you could load here; but usually loaded globally)
        var turnLoaded = false;
        function getBookSize() {
            const maxW = 1000, maxH = 700;
            const w = Math.min(window.innerWidth * 0.98, maxW);
            const h = Math.min(window.innerHeight * 0.85, maxH);
            return { w, h };
        }
        function initTurnJs() {
            const size = getBookSize();
            $('#flipbook').turn({
                width: size.w,
                height: size.h,
                autoCenter: true,
                display: 'double',
                elevation: 50,
                duration: 1000,
                gradients: true,
                cornerSize: 500
            });
        }
        function openSampleOverlay() {
            document.getElementById('sample-overlay').style.display = 'flex';
            setTimeout(function () {
                if (!$('#flipbook').data('turn')) initTurnJs();
            }, 100);
        }
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('close-sample-btn').onclick = function () {
                document.getElementById('sample-overlay').style.display = 'none';
                if ($('#flipbook').data('turn')) {
                    $('#flipbook').turn('destroy');
                }
            };
            window.openSampleOverlay = openSampleOverlay;
            // Responsive resize
            window.addEventListener('resize', function () {
                if ($('#flipbook').data('turn')) {
                    const size = getBookSize();
                    $('#flipbook').turn('size', size.w, size.h);
                }
            });
        });
    </script>
    <!-- Turn.js (should be loaded after jQuery) -->
    <script src="{{ asset('js/turn.min.js') }}"></script>
    @include('components.floating-cart')
    @include('components.footer')
</body>

</html>