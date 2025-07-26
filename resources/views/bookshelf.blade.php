<!DOCTYPE html>
<html lang="en" x-data="{ search: '{{ $query ?? '' }}', loading: false }">
<head>
    <meta charset="UTF-8">
    <title>Bookshelf</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Order: Bootstrap -> Your CSS -> Tailwind --}}
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookshelf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    @include('components.header')

    <div class="container mx-auto py-32 px-4" style="max-width:1024px;">
        <h1 class="text-3xl font-bold mb-8 text-center">Bookshelf</h1>
        <!-- Live Search Form with Alpine.js -->
        <form action="{{ route('books.bookshelf') }}" method="GET"
              class="flex flex-col sm:flex-row justify-center mb-8 gap-2 search-form"
              @submit="loading = false">

            <div class="relative w-full max-w-md flex">
                <input
                    type="text"
                    name="q"
                    x-model="search"
                    placeholder="Search books by title or author..."
                    class="w-full px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    autocomplete="off"
                    autofocus
                    @input.debounce.300ms="
                        loading = true;
                        fetch('{{ route('books.ajaxSearch') }}?q=' + encodeURIComponent(search))
                            .then(response => response.text())
                            .then(html => {
                                $refs.results.innerHTML = html;
                                loading = false;
                            });
                    "
                />
                <button
                    type="button"
                    x-show="search.length"
                    @click="search=''; $refs.results.innerHTML = $refs.originalResults.innerHTML;"
                    class="absolute right-9 top-2 text-gray-400 hover:text-gray-700"
                    aria-label="Clear"
                >
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-r hover:bg-indigo-700 transition">
                <i class="fa fa-search"></i>
            </button>
        </form>
        <!-- Loader (optional) -->
        <div x-show="loading" class="text-center mb-4">
            <i class="fa fa-spinner fa-spin text-indigo-600"></i>
            <span class="text-indigo-600 ml-2">Searching...</span>
        </div>
        <!-- Results Grid -->
        <div class="covers-grid" x-ref="results">
            @include('components.bookshelf-results', ['books' => $books])
        </div>
        <!-- Hidden original grid for restoring on clear -->
        <div x-ref="originalResults" class="hidden">
            @include('components.bookshelf-results', ['books' => $books])
        </div>

        {{-- PAGINATION (only for classic search, not AJAX) --}}
        <template x-if="!search.length">
            <div>
                @if($books->hasPages())
                    <div class="pagination mt-8">
                        {{ $books->withQueryString()->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </div>
        </template>
    </div>

    @include('components.floating-cart')
    @include('components.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    <script>
        // Only works if you have #add-to-cart-btn in this view.
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function () {
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
        }
    </script>
</body>
</html>