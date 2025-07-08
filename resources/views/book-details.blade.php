<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $book['title'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book-details.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,600,700,800&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- TailwindCSS (if you use Tailwind utility classes) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!-- Add other CSS as needed -->
</head>

<body>
    @include('components.header')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-10 bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <!-- Book Cover with Hover -->
            <div class="flex-shrink-0 flex flex-col items-center md:items-start">
                <div class="rounded-xl overflow-hidden shadow-lg bg-gray-100 mb-6 book-cover-hover">
                    <img src="{{ asset('storage/' . $book->cover_image_front) }}" alt="{{ $book['title'] }}"
                        class="w-48 h-72 object-cover" />
                </div>
                <button
                    class="px-6 py-2 rounded-lg border-2 border-indigo-600 text-indigo-600 font-semibold hover:bg-indigo-50 transition mb-4">
                    Read Sample
                </button>
                <div class="flex flex-col gap-2 w-full">
                    <button
                        class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Hard Copy ₹{{ $book['price'] ?? '1690' }} <span
                            class="line-through text-gray-400 text-sm ml-2">2990</span>
                    </button>
                    <button
                        class="w-full px-4 py-2 rounded-lg bg-indigo-100 text-indigo-700 font-semibold hover:bg-indigo-200 transition">
                        Digital Copy ₹990 <span class="line-through text-gray-400 text-sm ml-2">1690</span>
                    </button>
                    <button id="add-to-cart-btn"
                        class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition mt-4"
                        data-book-id="{{ $book['id'] }}" type="button">
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
                <div class="mb-2 text-sm text-indigo-600 font-semibold uppercase tracking-wide">
                    BY {{ strtoupper($book['author']) }}
                    <span class="ml-2 text-pink-600 font-bold">4.2k Learners</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">{{ $book['title'] }}</h1>
                <div class="text-lg text-gray-500 mb-4">
                    {{ $book['subtitle'] ?? 'How To Write and Sell Non-Fictional Books' }}
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-400 text-xl">⭐⭐⭐⭐☆</span>
                    <span class="text-gray-700 text-sm">4.5 | <a href="#" class="text-indigo-600 underline">5
                            Reviews</a></span>
                </div>
                <div class="flex flex-wrap gap-2 mb-8">
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">30 days - 14
                        pages per day</span>
                    <span
                        class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">English</span>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">439</span>
                    <span
                        class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold">Intermediate</span>
                </div>
                <section class="mb-8">
                    <h2 class="text-indigo-600 font-bold text-base mb-2 tracking-wide">WHAT YOU’LL LEARN</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-gray-800 text-sm">
                        <div>✔️ How to write, structure and sell a non-fiction book</div>
                        <div>✔️ The Publishing process, whether it is for self-publishing or to publish the book through
                            an agency</div>
                        <div>✔️ The online and offline marketing channels you can leverage to promote and sell your book
                        </div>
                        <div>✔️ How to refine and enhance your written context and make it market ready</div>
                        <div>✔️ Learn about your rights as an Author</div>
                        <div>✔️ How to create a brand for your book</div>
                    </div>
                </section>
                <section>
                    <h2 class="text-indigo-600 font-bold text-base mb-2 tracking-wide">DESCRIPTION</h2>
                    <p class="text-gray-700 text-base leading-relaxed">
                        {{ $book['description'] ?? '"The Bookpreneur" is a comprehensive guide for new and established authors, offering insights into publishing and marketing. It covers the entire process from idea conception to sales, exploring market research, book structuring, and author mindset. The handbook examines both traditional and self-publishing paths, discussing business aspects like contracts, pricing, and distribution. It addresses legal issues such as plagiarism and copyright. The guide also delves into marketing strategies, including online and offline methods, branding, and more.' }}
                    </p>
                </section>
                <div class="flex gap-4 mt-auto">
                    <a href="{{ route('books.show', $book->id) }}"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                        Book Details
                    </a>
                    <a href="{{ route('books.read', $book->id) }}"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                        Read Book
                    </a>
                </div>
            </div>
        </div>
        {{-- @include('components.suggested-books', ['suggestedBooks' => $suggestedBooks]) --}}
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

    @include('components.floating-cart')
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
    </script>
</body>

</html>