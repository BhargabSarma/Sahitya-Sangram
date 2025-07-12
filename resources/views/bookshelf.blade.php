<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bookshelf</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookshelf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- Bootstrap CSS -->
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
    <div class="container mx-auto py-32 px-4"> <!-- Changed py-12 to py-24 for more top spacing -->
        <h1 class="text-3xl font-bold mb-8 text-center">Bookshelf</h1>
        <form action="{{ route('books.bookshelf') }}" method="GET" class="flex justify-center mb-8">
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search books by title or author..."
                class="w-full max-w-md px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-indigo-400" />
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-r hover:bg-indigo-700 transition">
                <i class="fa fa-search"></i>
            </button>
        </form>
        <div class="covers-grid">
            @forelse($books as $book)
                <a href="{{ route('books.show', $book['id']) }}" class="cover-card" title="{{ $book['title'] }}">
                    @if($book->cover_image_front)
                        <img src="{{  asset($book->cover_image_front }}" class="card-img-top" alt="Front Cover">
                    @else
                        <img src="{{ asset('images/default_cover.jpg') }}" class="card-img-top" alt="No Cover">
                    @endif
                    {{-- Optionally show back cover --}}
                    @if($book->cover_image_back)
                        <img src="{{ asset('storage/' . $book->cover_image_back) }}" class="card-img-top mt-2" alt="Back Cover">
                    @endif
                </a>
            @empty
                <div class="col-span-full text-center text-gray-500">No books found.</div>
            @endforelse
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
    @include('components.floating-cart')


    @include('components.footer')
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