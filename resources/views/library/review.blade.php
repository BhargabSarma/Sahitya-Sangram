<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review: {{ $book->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Review faces CSS -->
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
    <!-- Bootstrap CSS (optional, for layout) -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f7f8fa !important;
            font-family: 'Inter', Arial, sans-serif;
        }
    </style>
</head>
<body>
<div class="container py-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Review: {{ $book->title }}</h2>
    <form action="{{ route('library.review.store', $book->id) }}" method="POST" class="p-4 border rounded shadow-sm bg-white">
        @csrf
        <div class="mb-4 text-center">
            <label class="form-label mb-2" style="font-weight: 500;">Your Rating</label>
            <div class="feedback d-flex justify-content-center mb-2" data-book-id="{{ $book->id }}">
                <label class="angry">
                    <input type="radio" value="1" name="rating" />
                    <div>
                        <svg class="eye left"><use xlink:href="#eye"></use></svg>
                        <svg class="eye right"><use xlink:href="#eye"></use></svg>
                        <svg class="mouth"><use xlink:href="#mouth"></use></svg>
                    </div>
                </label>
                <label class="sad">
                    <input type="radio" value="2" name="rating" />
                    <div>
                        <svg class="eye left"><use xlink:href="#eye"></use></svg>
                        <svg class="eye right"><use xlink:href="#eye"></use></svg>
                        <svg class="mouth"><use xlink:href="#mouth"></use></svg>
                    </div>
                </label>
                <label class="ok">
                    <input type="radio" value="3" name="rating" />
                    <div></div>
                </label>
                <label class="good">
                    <input type="radio" value="4" name="rating" />
                    <div>
                        <svg class="eye left"><use xlink:href="#eye"></use></svg>
                        <svg class="eye right"><use xlink:href="#eye"></use></svg>
                        <svg class="mouth"><use xlink:href="#mouth"></use></svg>
                    </div>
                </label>
                <label class="happy">
                    <input type="radio" value="5" name="rating" checked />
                    <div>
                        <svg class="eye left"><use xlink:href="#eye"></use></svg>
                        <svg class="eye right"><use xlink:href="#eye"></use></svg>
                    </div>
                </label>
            </div>
            <!-- SVG Faces Symbols -->
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 4" id="eye">
                    <path d="M1,1 C1.83333333,2.16666667 2.66666667,2.75 3.5,2.75 C4.33333333,2.75 5.16666667,2.16666667 6,1"></path>
                </symbol>
                <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 7" id="mouth">
                    <path d="M1,5.5 C3.66666667,2.5 6.33333333,1 9,1 C11.6666667,1 14.3333333,2.5 17,5.5"></path>
                </symbol>
            </svg>
        </div>
        <div class="mb-4">
            <label for="comment" class="form-label" style="font-weight: 500;">Comment</label>
            <textarea name="comment" class="form-control" rows="4" maxlength="500" style="resize: vertical;" required placeholder="Write your thoughts..."></textarea>
        </div>
        <button type="submit" class="btn btn-dark w-100">Submit Review</button>
        @if(session('error'))
            <div class="alert alert-danger mt-3 text-center">{{ session('error') }}</div>
        @endif
    </form>
</div>
</body>
</html>