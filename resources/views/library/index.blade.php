<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Library - Sahityaa Sangamm</title>
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
    <style>
        body {
            background: #f7f8fa !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card {
            transition: box-shadow 0.2s;
            border-radius: 1rem !important;
            border: none;
            background: #fff;
        }
        .card:hover {
            box-shadow: 0 4px 24px 0 rgba(44,62,80,.10), 0 1.5px 5px 0 rgba(44,62,80,.06);
        }
        .btn-primary {
            background: #006eff;
            border: none;
        }
        .btn-outline-primary {
            border: 2px solid #006eff;
            color: #006eff;
            background: #fff;
        }
        .btn-outline-primary:hover {
            background: #e6f0ff;
            color: #0056b3;
            border-color: #0056b3;
        }
        .btn-outline-success {
            border: 2px solid #36b37e;
            color: #36b37e;
            background: #fff;
        }
        .btn-outline-success:hover {
            background: #36b37e;
            color: #fff;
        }
        .text-warning {
            color: #ffb100 !important;
        }
        .rounded-xl {
            border-radius: 1rem !important;
        }
        .h-56 {
            height: 224px !important;
        }

        .library-title-section {
            padding-top: 10rem;
            padding-bottom: 2rem;
        }
        @media (max-width: 576px) {
            .h-56 { height: 180px !important; }
            .library-title-section {
                padding-top: 10rem;
                padding-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
@include('components.header')
    <!-- Main Content -->
    <main class="container flex-grow-1">
        <div class="library-title-section d-flex flex-column align-items-center mb-4">
            <h2 class="mb-0 text-3xl fw-bold text-dark text-center">My Library</h2>
        </div>
        @if ($books->isEmpty())
            <div class="alert alert-info text-center py-5 rounded shadow-sm bg-white">
                <p class="mb-4 fs-5">You have not purchased any books yet.</p>
                <a href="{{ route('books.bookshelf') }}" class="btn btn-primary rounded-pill px-5 py-2 fs-5 fw-semibold shadow hover:bg-primary">
                    <i class="fa fa-plus me-2"></i>Buy Books
                </a>
            </div>
        @else
            <div class="row g-4 justify-content-center">
                @foreach ($books as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                        <div class="card flex-fill shadow-sm border-0 bg-white rounded-xl h-100">
                            @if($book->cover_image_front)
                                <img src="{{ asset('storage/' . ltrim($book->cover_image_front, '/')) }}" alt="{{ $book->title }}"
                                    class="w-100 h-56 object-fit-cover rounded-top border-bottom">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center h-56 rounded-top">
                                    <span class="text-muted">No Image</span>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title mb-1 fw-semibold fs-5 text-dark">{{ $book->title }}</h5>
                                @if($book->subtitle)
                                    <p class="card-text mb-3 text-muted fs-6">{{ $book->subtitle }}</p>
                                @endif
                                <a href="{{ route('books.read', $book->id) }}" class="btn btn-primary btn-block mb-3 w-100 rounded-pill fw-semibold shadow-sm">
                                    <i class="fa fa-book me-2"></i>Read Now
                                </a>
                                @if(isset($reviews[$book->id]))
                                    <div class="alert alert-secondary py-2 px-3 mt-auto mb-0 rounded bg-light border-0">
                                        <strong class="d-block fs-6 mb-1 text-dark">Your Review</strong>
                                        <span>
                                            <span class="text-warning fs-5 align-middle">
                                                {!! str_repeat('&#9733;', $reviews[$book->id]->rating) !!}
                                                {!! str_repeat('&#9734;', 5 - $reviews[$book->id]->rating) !!}
                                            </span>
                                            <span class="fs-6 text-muted">({{ $reviews[$book->id]->rating }}/5)</span>
                                        </span>
                                        <p class="fs-6 text-secondary mt-1 mb-0">"{{ $reviews[$book->id]->comment }}"</p>
                                    </div>
                                @else
                                    <a href="{{ route('library.review.form', $book->id) }}" class="btn btn-outline-success btn-sm mt-auto w-100 rounded-pill shadow-sm">
                                        <i class="fa fa-star me-1"></i>Leave a Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('books.bookshelf') }}" class="btn btn-outline-primary rounded-pill px-5 py-2 fs-5 fw-semibold shadow-sm">
                    <i class="fa fa-plus me-2"></i>Buy More Books
                </a>
            </div>
        @endif
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