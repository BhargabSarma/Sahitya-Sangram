<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $book->title }}</title>
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
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .shelf-books__item {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .flip-container {
            perspective: 1200px;
            width: 100%;
            height: 100%;
        }

        .flipper {
            transition: 0.7s;
            transform-style: preserve-3d;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .flip-container.flipped .flipper {
            transform: rotateY(180deg);
        }

        .shelf-books__container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .shelf-books__cover {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .shelf-books__back-cover {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #1e293b;
            border-radius: 14px;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .shelf-books__inside {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .shelf-books__page {
            width: 92%;
            height: 92%;
            background: #f1f5f9;
            border-radius: 10px;
            position: absolute;
            left: 4%;
            top: 4%;
            box-shadow: 0 1px 5px rgba(60, 60, 100, 0.12);
        }

        .shelf-books__page:nth-child(1) {
            z-index: 3;
        }

        .shelf-books__page:nth-child(2) {
            z-index: 2;
            left: 6%;
            top: 6%;
        }

        .shelf-books__page:nth-child(3) {
            z-index: 1;
            left: 8%;
            top: 8%;
        }

        .shelf-books__image {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 10;
            border-radius: 14px;
            overflow: hidden;
        }

        .shelf-books__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
        }

        .shelf-books__effect {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 20;
        }

        .shelf-books__light {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 25;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.07) 0%, rgba(255, 255, 255, 0.23) 90%);
            border-radius: 14px;
        }

        .shelf-books__hitbox {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 50;
            cursor: pointer;
        }

        .flip-container .back {
            transform: rotateY(180deg);
        }

        .flip-container .back,
        .flip-container .front {
            backface-visibility: hidden;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        #peek-flipbook {
            width: 100%;
            height: 600px;
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border-radius: 0.75rem;
            margin-left: auto;
            margin-right: auto;
            transition: transform 0.35s cubic-bezier(.5, 1.7, .85, .67), box-shadow 0.35s;
        }

        #peek-flipbook .page {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #peek-flipbook .page-content {
            width: 92%;
            height: 95%;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #peek-flipbook .page-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 0.5rem;
        }

        #peek-flipbook .page-number {
            position: absolute;
            bottom: 8px;
            right: 15px;
            font-size: 0.875rem;
            color: #9ca3af;
        }

        @media (max-width: 1024px) {
            #peek-flipbook {
                height: 55vw;
                min-height: 400px;
            }
        }

        @media (max-width: 768px) {
            #peek-flipbook {
                height: 65vw;
                min-height: 350px;
            }
        }

        @media (max-width: 480px) {
            #peek-flipbook {
                width: 90vw !important;
                height: 45vw !important;
                min-width: 0 !important;
                max-width: 95vw !important;
                margin: 0 auto !important;
            }

            #peek-flipbook .page-number {
                font-size: 0.75rem;
                bottom: 6px;
                right: 10px;
            }
        }

        body,
        html {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="text-slate-800">

    @include('components.header')

    <main class="py-12 md:py-16 lg:py-32">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-x-12">

                <aside class="lg:col-span-4 mb-10 lg:mb-0">
                    <div class="sticky top-8 flex flex-col items-center">

                        <div class="relative mx-auto w-[222px] h-[314px] mt-16 mb-6 shadow-2xl rounded-2xl">
                            <div class="shelf-books__item">
                                <div class="flip-container" id="book-flip">
                                    <div class="flipper">
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
                                                        alt="{{ $book->title }} Front Cover" />
                                                    <div class="shelf-books__effect"></div>
                                                    <div class="shelf-books__light"></div>
                                                </div>
                                                <div class="shelf-books__hitbox"></div>
                                            </div>
                                        </div>
                                        <div
                                            class="back shelf-books__container flex items-center justify-center bg-slate-800">
                                            <div class="shelf-books__image">
                                                <img src="{{ asset('storage/' . $book->cover_image_back) }}"
                                                    alt="{{ $book->title }} Back Cover" />
                                                <div class="shelf-books__effect"></div>
                                                <div class="shelf-books__light"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full max-w-xs flex flex-col gap-3">
                            <a href="{{ route('books.read', ['id' => $book->id]) }}"
                                class="w-full text-center px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold text-base shadow-sm hover:bg-indigo-500 transition-colors duration-200">
                                Read Now
                            </a>
                            <div class="space-y-3">
                                <div id="price-selector" class="grid grid-cols-2 gap-2">
                                    <button data-price-type="hard_copy"
                                        class="price-btn w-full py-2 rounded-md font-semibold bg-indigo-100 text-indigo-700 ring-2 ring-indigo-600">Hard
                                        Copy</button>
                                    <button data-price-type="digital_copy"
                                        class="price-btn w-full py-2 rounded-md font-semibold bg-white text-slate-600 ring-1 ring-slate-300">Digital</button>
                                </div>
                                <button id="add-to-cart-btn"
                                    class="w-full text-center px-6 py-3 rounded-lg bg-slate-800 text-white font-semibold text-base shadow-sm hover:bg-slate-700 transition-colors duration-200"
                                    data-book-id="{{ $book->id }}">
                                    Add to Cart <span id="price-display"
                                        class="ml-2 font-bold">₹{{ $book->hard_copy_price}}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="lg:col-span-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-1 rounded-full">Book</span>
                        <span
                            class="bg-pink-100 text-pink-800 text-xs font-semibold px-2.5 py-1 rounded-full">New</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                        {{ $book->title }}
                    </h1>
                    @if($book->subtitle)
                        <p class="mt-2 text-lg sm:text-xl text-slate-600">{{ $book->subtitle }}</p>
                    @endif
                    <p class="mt-4 text-base sm:text-lg font-medium text-slate-700">By
                        {{ $book->author->name ?? 'AUTHOR' }}
                    </p>
                    <div class="flex items-center gap-2 mt-4 flex-wrap">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 {{ ($book->rating ?? 4.5) > $i ? 'text-yellow-400' : 'text-slate-300' }}"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-slate-600 text-sm font-medium">{{ $book->rating ?? '4.5' }} out of 5</span>
                        <span class="hidden sm:inline text-slate-400">·</span>
                        <a href="#"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 w-full sm:w-auto">{{ $book->review_count ?? 5 }}
                            Reviews</a>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <h2 class="text-base font-semibold text-slate-900">Details</h2>
                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6-2.292m0 0v14.25" />
                                </svg>
                                <span class="text-slate-600 font-medium">{{ $book->number_of_pages ?? '439' }}
                                    pages</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C13.18 7.061 14.122 7.5 15 7.5c.878 0 1.82-.439 2.666-1.136" />
                                </svg>
                                <span class="text-slate-600 font-medium">{{ $book->language ?? 'English' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.343 3.94c.09-.542.56-1.007 1.11-1.227l2.894-.87c.61-.184 1.25.266 1.25.907v5.623c0 .48-.344.89-.815.98l-3.32.553c-.57.095-1.022-.44-1.022-1.007V3.94zM19.5 9.75l-3.32.553c-.57.095-1.022-.44-1.022-1.007V3.94c0-.542.56-1.007 1.11-1.227l2.894-.87c.61-.184 1.25.266 1.25.907v5.623c0 .48-.344.89-.815.98zM4.5 9.75l3.32.553c.57.095 1.022-.44 1.022-1.007V3.94c0-.542-.56-1.007-1.11-1.227L4.814 2.713a1.125 1.125 0 00-1.25.907v5.623c0 .48.344.89.815.98z" />
                                </svg>
                                <span class="text-slate-600 font-medium">{{ $book->level ?? 'Intermediate' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h2 class="text-base font-semibold text-slate-900">Description</h2>
                        <div class="mt-4 space-y-4 text-base text-slate-600 leading-relaxed">
                            {{ $book->description ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <section class="py-12 md:py-16 lg:py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Sneak Peek</h2>
                <p class="mt-3 max-w-2xl mx-auto text-lg text-slate-500">Flip through a few pages to get a feel for the
                    book.</p>
            </div>
            <div class="mt-12">
                <div id="peek-flipbook">
                    <div class="page"></div>
                    @foreach($samplePages as $p)
                        <div class="page">
                            <div class="page-content">
                                @if(!empty($p['image']))
                                    <img src="{{ $p['image'] }}" alt="Sample Page {{ $p['number'] }}">
                                @elseif(!empty($p['content']))
                                    <div class="text-sm text-slate-600 p-8 text-center">{{ $p['content'] }}</div>
                                @endif
                                @if(!empty($p['number']))
                                    <div class="page-number">{{ $p['number'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/turn.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    <script>
        function getFlipbookDisplayMode() {
            return window.innerWidth < 700 ? 'single' : 'double';
        }
        function initializeFlipbook() {
            const flipbook = $('#peek-flipbook');
            if (window.jQuery && flipbook.length) {
                if (flipbook.data('turn')) { flipbook.turn('destroy'); }
                flipbook.turn({
                    width: flipbook.width(),
                    height: flipbook.height(),
                    autoCenter: true,
                    display: getFlipbookDisplayMode(),
                    elevation: 50,
                    duration: 800,
                    gradients: true,
                    page: 2,
                    when: { turning: (event, page) => { if (page === 1) event.preventDefault(); } }
                });
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined" && typeof CustomEase !== "undefined") {
                gsap.registerPlugin(ScrollTrigger, CustomEase);
                CustomEase.create("bookEase", "0.25, 1, 0.5, 1");
                gsap.utils.toArray(".shelf-books__item").forEach((book, i) => {
                    gsap.from(book, {
                        scrollTrigger: {
                            trigger: book,
                            start: "top 85%",
                            toggleActions: "play none none none"
                        },
                        y: 60,
                        opacity: 0,
                        duration: 0.8,
                        delay: i * 0.1,
                        ease: "power3.out"
                    });
                });
            }
            const books = document.querySelectorAll(".shelf-books__item");
            books.forEach((book) => {
                const flipContainer = book.querySelector('.flip-container') || document.getElementById('book-flip');
                const hitbox = book.querySelector(".shelf-books__hitbox");
                const bookImage = book.querySelector(".shelf-books__image");
                const bookEffect = book.querySelector(".shelf-books__effect");
                const pages = book.querySelectorAll(".shelf-books__page");
                const bookLight = book.querySelector(".shelf-books__light");
                if (bookImage) {
                    gsap.set(bookImage, { boxShadow: "0 10px 20px rgba(0,0,0,0.15), 0 30px 45px rgba(0,0,0,0.12), 0 60px 80px rgba(0,0,0,0.1)" });
                }
                if (bookLight) {
                    gsap.set(bookLight, { opacity: 0.1, rotateY: 0 });
                }
                if (pages.length) {
                    gsap.set(pages, { x: 0 });
                }
                const hoverIn = gsap.timeline({ paused: true });
                if (bookImage) {
                    hoverIn.to(bookImage, { duration: 0.7, rotateY: -12, translateX: -6, scaleX: 0.96, boxShadow: "10px 10px 20px rgba(0,0,0,0.25), 20px 20px 40px rgba(0,0,0,0.2), 40px 40px 60px rgba(0,0,0,0.15)", ease: "bookEase" }, 0);
                }
                if (bookEffect) {
                    hoverIn.to(bookEffect, { duration: 0.7, marginLeft: 10, ease: "bookEase" }, 0);
                }
                if (bookLight) {
                    hoverIn.to(bookLight, { duration: 0.7, opacity: 0.2, rotateY: -12, ease: "bookEase" }, 0);
                }
                if (pages.length) {
                    hoverIn.to(pages[0], { x: "4px", duration: 0.7, ease: "power1.inOut" }, 0);
                    if (pages[1]) hoverIn.to(pages[1], { x: "2px", duration: 0.7, ease: "power1.inOut" }, 0);
                    if (pages[2]) hoverIn.to(pages[2], { x: "0px", duration: 0.7, ease: "power1.inOut" }, 0);
                }
                if (hitbox) {
                    hitbox.addEventListener("mouseenter", () => hoverIn.play());
                    hitbox.addEventListener("mouseleave", () => hoverIn.reverse());
                    hitbox.addEventListener("click", () => {
                        if (flipContainer) {
                            flipContainer.classList.toggle('flipped');
                        }
                    });
                }
            });
            const priceSelector = document.getElementById('price-selector');
            const priceDisplay = document.getElementById('price-display');
            const addCartBtn = document.getElementById('add-to-cart-btn');
            const bookEditions = {
                hard_copy: { name: 'Hard Copy', price: '{{ $book->hard_copy_price }}', type: 'hard_copy' },
                digital_copy: { name: 'Digital', price: '{{ $book->digital_price  }}', type: 'digital_copy' }
            };
            let selectedType = 'hard_copy';
            if (priceSelector) {
                priceSelector.addEventListener('click', function (e) {
                    const button = e.target.closest('.price-btn');
                    if (!button || button.dataset.priceType === selectedType) return;
                    selectedType = button.dataset.priceType;
                    priceSelector.querySelectorAll('.price-btn').forEach(btn => {
                        btn.classList.remove('bg-indigo-100', 'text-indigo-700', 'ring-2', 'ring-indigo-600');
                        btn.classList.add('bg-white', 'text-slate-600', 'ring-1', 'ring-slate-300');
                    });
                    button.classList.add('bg-indigo-100', 'text-indigo-700', 'ring-2', 'ring-indigo-600');
                    button.classList.remove('bg-white', 'text-slate-600', 'ring-1', 'ring-slate-300');
                    if (priceDisplay && bookEditions[selectedType]) {
                        priceDisplay.textContent = '₹' + bookEditions[selectedType].price;
                    }
                });
            }
            if (addCartBtn) {
                addCartBtn.addEventListener('click', function () {
                    const bookId = this.getAttribute('data-book-id');
                    const edition = bookEditions[selectedType];
                    const cartData = {
                        type: edition.type,
                        price: edition.price
                    };
                    const fetchUrl = `{{ url('/cart/add') }}/${bookId}`;
                    addCartBtn.disabled = true;
                    addCartBtn.textContent = 'Adding...';
                    fetch(fetchUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(cartData)
                    })
                        .then(response => {
                            if (response.status === 401) {
                                window.location.href = '/login';
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.cart_count !== undefined) {
                                const cartCountElem = document.getElementById('cart-count');
                                if (cartCountElem) {
                                    cartCountElem.textContent = data.cart_count;
                                }
                                addCartBtn.textContent = 'Added!';
                                setTimeout(() => {
                                    addCartBtn.innerHTML = `Add to Cart <span id="price-display" class="ml-2 font-bold">₹${edition.price}</span>`;
                                    addCartBtn.disabled = false;
                                }, 1500);
                            } else {
                                addCartBtn.textContent = 'Error!';
                                addCartBtn.disabled = false;
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            addCartBtn.textContent = 'Error!';
                            addCartBtn.disabled = false;
                        });
                });
            }
            initializeFlipbook();
            window.addEventListener('resize', function () {
                setTimeout(initializeFlipbook, 100);
            });

            // Phone-only tap-to-flip logic
            function isMobile() {
                return window.innerWidth <= 600;
            }
            const flipContainer = document.getElementById('book-flip');
            if (flipContainer && isMobile()) {
                flipContainer.onclick = function (event) {
                    const box = flipContainer.getBoundingClientRect();
                    const x = event.clientX - box.left;
                    if (x < box.width / 2) {
                        flipContainer.classList.remove('flipped');
                    } else {
                        flipContainer.classList.add('flipped');
                    }
                };
            }
            window.addEventListener('resize', function () {
                if (flipContainer) {
                    if (isMobile()) {
                        flipContainer.onclick = function (event) {
                            const box = flipContainer.getBoundingClientRect();
                            const x = event.clientX - box.left;
                            if (x < box.width / 2) {
                                flipContainer.classList.remove('flipped');
                            } else {
                                flipContainer.classList.add('flipped');
                            }
                        };
                    } else {
                        flipContainer.onclick = null;
                    }
                }
            });
        });
    </script>

    @include('components.floating-cart')
    @include('components.footer')

</body>

</html>