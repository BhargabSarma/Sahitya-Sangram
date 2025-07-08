<!DOCTYPE html>
<html>

<head>
    <title>Book Reader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            margin: 0;
        }

        #reader {
            max-width: 800px;
            margin: auto;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .controls {
            margin: 20px;
        }

        .page-num {
            font-size: 16px;
            margin: 10px;
        }
    </style>
</head>

<body>
    <div id="reader">
        <h3 class="mb-4">{{ $book->title ?? '' }}</h3>
        <div class="controls">
            <button id="prev-btn">⬅️ Prev</button>
            <span class="page-num">Page <span id="current-page">1</span> / {{ $pageCount ?? 1 }}</span>
            <button id="next-btn">Next ➡️</button>
        </div>
        <img id="book-page" src="" loading="lazy" alt="Loading..." oncontextmenu="return false;">
    </div>

    <script>
        let currentPage = 1;
        const bookId = '{{ $bookId }}';
        const pageCount = {{ $pageCount ?? 1 }};
        const imgEl = document.getElementById('book-page');
        const pageDisplay = document.getElementById('current-page');

        function loadPage(page) {
            imgEl.src = `/books/${bookId}/${page}`;
            pageDisplay.textContent = page;
            document.getElementById('prev-btn').disabled = (page <= 1);
            document.getElementById('next-btn').disabled = (page >= pageCount);
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                loadPage(currentPage);
            }
        }

        function nextPage() {
            if (currentPage < pageCount) {
                currentPage++;
                loadPage(currentPage);
            }
        }

        document.getElementById('prev-btn').addEventListener('click', prevPage);
        document.getElementById('next-btn').addEventListener('click', nextPage);

        // Load first page
        loadPage(currentPage);
    </script>
</body>

</html>

{{-- @extends('layouts.app')
@section('title', "Read {$book->title}")

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper {
        width: 100%;
        height: 80vh;
    }

    .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #333;
        position: relative;
    }

    .page-img {
        max-width: 98vw;
        max-height: 75vh;
        user-select: none;
        -webkit-user-drag: none;
        pointer-events: none;
    }

    .img-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
</style>

<div class="container mt-3">
    <h3 class="mb-4">{{ $book->title }}</h3>
    <div class="swiper">
        <div class="swiper-wrapper">
            @for($i = 1; $i <= $pageCount; $i++) <div class="swiper-slide">
                <img src="{{ route('books.page', ['book' => $book->id, 'page' => $i]) }}" class="page-img"
                    oncontextmenu="return false;" draggable="false" alt="Page {{ $i }}">
                <div class="img-overlay" oncontextmenu="return false;" onmousedown="return false;"></div>
        </div>
        @endfor
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
</div>

<!-- Security JS -->
<script>
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', function (e) {
        // Disable PrintScreen, Ctrl+S, Ctrl+U, F12 (not foolproof, but slows down users)
        if (e.key === "PrintScreen" || (e.ctrlKey && ['s', 'u'].includes(e.key.toLowerCase())) || e.key === "F12") {
            e.preventDefault();
            return false;
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        loop: false,
        pagination: { el: '.swiper-pagination', type: 'fraction' },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        spaceBetween: 32
    });
</script>
@endsection --}}