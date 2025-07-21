<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Reader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #18191a;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        #fullscreen-reader {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            background: #18191a;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            user-select: none;
            transition: background 0.2s;
        }
        .reader-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 16px 32px 0 32px;
            box-sizing: border-box;
            gap: 20px;
        }
        .reader-title {
            color: #fafafa;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-right: 16px;
        }
        .reader-mode {
            background: #222;
            color: #fafafa;
            border: none;
            border-radius: 8px;
            width: 46px;
            height: 38px;
            font-size: 1.25rem;
            cursor: pointer;
            transition: background .18s, color .18s, box-shadow .18s;
            user-select: none;
            margin-left: 0;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            outline: none;
        }
        .reader-mode.active {
            background: #ffe2b7;
            color: #ff8a00;
            box-shadow: 0 0 0 2px #ffd600;
        }
        .reader-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 18px 0 12px 0;
            gap: 1.2rem;
        }
        .reader-arrow, .reader-zoom {
            background: #222;
            color: #fafafa;
            border: none;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            font-size: 1.6rem;
            cursor: pointer;
            transition: background .18s;
            user-select: none;
            margin: 0 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reader-arrow:disabled {
            background: #444;
            color: #888;
            cursor: not-allowed;
        }
        .reader-page-num {
            color: #fafafa;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            margin: 0 1rem;
        }
        .reader-img-wrap {
            position: relative;
            width: 92vw;
            max-width: 950px;
            height: 78vh;
            background: #232426;
            border-radius: 14px;
            box-shadow: 0 6px 32px rgba(0,0,0,.34);
            display: flex;
            justify-content: center;
            align-items: center;
            user-select: none;
            overflow: hidden;
        }
        .book-page-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 50%;
            height: 100%;
            overflow: hidden;
            background: transparent;
            position: relative;
            transition: box-shadow .2s;
        }
        .book-page {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            transition: box-shadow .2s, transform .2s;
            cursor: pointer;
            background: #fff;
            box-shadow: 0 2px 12px #1114;
        }
        .book-page.selected {
            box-shadow: 0 0 0 4px #ffd600, 0 2px 12px #1114;
            z-index: 2;
        }
        .book-page-wrap.selected {
            background: #2223;
            overflow: auto;
        }
        .reader-exit {
            background: #222;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 0.4em 1.1em;
            font-size: 1.1em;
            cursor: pointer;
            opacity: 0.85;
            transition: opacity .2s, background .2s;
            margin-left: 16px;
        }
        .reader-exit:hover {
            opacity: 1;
            background: #d32f2f;
        }
        /* One page on mobile */
        @media (max-width: 800px) {
            .reader-img-wrap {
                width: 99vw;
                max-width: 99vw;
                height: 70vh;
            }
            .book-page-wrap {
                width: 100%;
            }
            .book-page {
                max-width: 96vw;
                max-height: 68vh;
                min-width: 120px;
                margin: 0;
            }
        }
        /* Warm effect ("reading mode") */
        #fullscreen-reader.read-mode {
            background: #fff4e2;
        }
        #fullscreen-reader.read-mode .reader-img-wrap {
            background: linear-gradient(120deg, #fff8e6 60%, #ffecd2 100%);
            box-shadow: none;
        }
        #fullscreen-reader.read-mode .book-page {
            background: #fffbe7;
            box-shadow: 0 8px 32px #ffd60044;
        }
        #fullscreen-reader.read-mode .reader-header,
        #fullscreen-reader.read-mode .reader-controls {
            opacity: 0.95;
        }
        #fullscreen-reader.read-mode .reader-title {
            color: #ff8a00;
        }
        #fullscreen-reader.read-mode .reader-page-num {
            color: #ff8a00;
        }
    </style>
</head>
<body>
    <div id="fullscreen-reader" tabindex="0">
        <div class="reader-header">
            <div class="reader-title">{{ $book->title ?? '' }}</div>
            <button class="reader-mode" id="toggle-read-mode" title="Reading Mode">&#9788;</button>
            <button class="reader-exit" id="exit-fullscreen" title="Exit Reader (Esc)">✕ Exit</button>
        </div>
        <div class="reader-controls">
            <button id="zoom-out" class="reader-zoom" title="Zoom Out">−</button>
            <button id="prev-btn" class="reader-arrow" title="Previous Page">&#8678;</button>
            <span class="reader-page-num">Page <span id="current-page">1</span> / {{ $pageCount ?? 1 }}</span>
            <button id="next-btn" class="reader-arrow" title="Next Page">&#8680;</button>
            <button id="zoom-in" class="reader-zoom" title="Zoom In">+</button>
        </div>
        <div class="reader-img-wrap" id="reader-img-wrap">
            <div class="book-page-wrap" id="wrap-left">
                <img id="book-page-left" class="book-page" src="" alt="Left Page" tabindex="0">
            </div>
            <div class="book-page-wrap" id="wrap-right">
                <img id="book-page-right" class="book-page" src="" alt="Right Page" tabindex="0">
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        const bookId = '{{ $bookId }}';
        const pageCount = {{ $pageCount ?? 1 }};
        const isDesktop = window.innerWidth >= 800;
        const imgLeft = document.getElementById('book-page-left');
        const imgRight = document.getElementById('book-page-right');
        const wrapLeft = document.getElementById('wrap-left');
        const wrapRight = document.getElementById('wrap-right');
        const pageDisplay = document.getElementById('current-page');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const reader = document.getElementById('fullscreen-reader');
        const exitBtn = document.getElementById('exit-fullscreen');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        const modeBtn = document.getElementById('toggle-read-mode');
        const bookshelfUrl = "{{ route('books.bookshelf') }}";

        let zoom = 1.0;
        let readMode = false;
        let selectedPage = 'left'; // 'left' or 'right'

        // ------ PAGE RENDER ------
        function loadPage(page) {
            if (isDesktop) {
                // Desktop: two pages at once
                let leftPage = page;
                let rightPage = (page < pageCount) ? page + 1 : null;
                imgLeft.src = `/books/${bookId}/${leftPage}`;
                imgLeft.style.display = 'block';
                if (rightPage) {
                    imgRight.src = `/books/${bookId}/${rightPage}`;
                    imgRight.style.display = 'block';
                } else {
                    imgRight.src = '';
                    imgRight.style.display = 'none';
                }
                pageDisplay.textContent = `${leftPage}${rightPage ? '–' + rightPage : ''}`;
                prevBtn.disabled = (leftPage <= 1);
                nextBtn.disabled = (rightPage ? rightPage >= pageCount : leftPage >= pageCount);
            } else {
                // Mobile: one page only
                imgLeft.src = `/books/${bookId}/${page}`;
                imgLeft.style.display = 'block';
                wrapRight.style.display = 'none';
                imgRight.style.display = 'none';
                pageDisplay.textContent = page;
                prevBtn.disabled = (page <= 1);
                nextBtn.disabled = (page >= pageCount);
            }
        }

        function prevPage() {
            if (isDesktop) {
                if (currentPage > 2) {
                    currentPage -= 2;
                } else if (currentPage > 1) {
                    currentPage = 1;
                }
            } else {
                if (currentPage > 1) {
                    currentPage--;
                }
            }
            loadPage(currentPage);
        }
        function nextPage() {
            if (isDesktop) {
                if (currentPage + 2 <= pageCount) {
                    currentPage += 2;
                } else if (currentPage + 1 <= pageCount) {
                    currentPage += 1;
                }
            } else {
                if (currentPage < pageCount) {
                    currentPage++;
                }
            }
            loadPage(currentPage);
        }

        // ------ SELECT PAGE TO ZOOM ------
        imgLeft.onclick = function() {
            selectedPage = 'left';
            setSelected();
        }
        imgRight.onclick = function() {
            selectedPage = 'right';
            setSelected();
        }
        function setSelected() {
            imgLeft.classList.toggle('selected', selectedPage === 'left');
            imgRight.classList.toggle('selected', selectedPage === 'right');
            wrapLeft.classList.toggle('selected', selectedPage === 'left');
            wrapRight.classList.toggle('selected', selectedPage === 'right');
        }

        // ------ ZOOM BUTTONS ------
        zoomInBtn.onclick = function() { setZoom(0.2); }
        zoomOutBtn.onclick = function() { setZoom(-0.2); }
        function setZoom(delta) {
            zoom = Math.max(1, Math.min(3, zoom + delta));
            if (selectedPage === 'left') {
                imgLeft.style.transform = `scale(${zoom})`;
                wrapLeft.style.overflow = zoom > 1 ? 'auto' : 'hidden';
            } else {
                imgRight.style.transform = `scale(${zoom})`;
                wrapRight.style.overflow = zoom > 1 ? 'auto' : 'hidden';
            }
        }

        // ------ READING MODE ------
        modeBtn.addEventListener('click', () => {
            readMode = !readMode;
            reader.classList.toggle('read-mode', readMode);
            modeBtn.classList.toggle('active', readMode);
        });

        // ------ SECURITY ------
        reader.addEventListener('mousedown', function(e) {
            // Allow only navigation and control buttons
            if (
                ![prevBtn, nextBtn, exitBtn, zoomInBtn, zoomOutBtn, modeBtn, imgLeft, imgRight].includes(e.target)
            ) {
                e.preventDefault();
                return false;
            }
        });
        reader.addEventListener('contextmenu', e => e.preventDefault());
        imgLeft.addEventListener('dragstart', e => e.preventDefault());
        imgRight.addEventListener('dragstart', e => e.preventDefault());
        imgLeft.addEventListener('selectstart', e => e.preventDefault());
        imgRight.addEventListener('selectstart', e => e.preventDefault());

        // ------ KEYBOARD ------
        reader.addEventListener('keydown', function(e) {
            if (e.key === "ArrowLeft") prevPage();
            if (e.key === "ArrowRight") nextPage();
            if (e.key === "+") setZoom(0.2);
            if (e.key === "-") setZoom(-0.2);
            if (e.key === "r" || e.key === "R") modeBtn.click();
            if (e.key === "Escape") exitReader();
        });

        // ------ TOUCH SWIPE ------
        let touchStartX = null;
        reader.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) touchStartX = e.touches[0].clientX;
        });
        reader.addEventListener('touchend', function(e) {
            if (touchStartX !== null && e.changedTouches.length === 1) {
                let deltaX = e.changedTouches[0].clientX - touchStartX;
                if (deltaX > 40) prevPage();
                if (deltaX < -40) nextPage();
                touchStartX = null;
            }
        });

        // ------ EXIT ------
        function exitReader() {
            window.location.href = bookshelfUrl;
        }
        exitBtn.addEventListener('click', exitReader);

        // ------ FULLSCREEN API ------
        function enterFullscreen() {
            if (reader.requestFullscreen) reader.requestFullscreen();
            else if (reader.webkitRequestFullscreen) reader.webkitRequestFullscreen();
        }
        // Enter fullscreen on load (desktop only)
        if (isDesktop) setTimeout(enterFullscreen, 400);

        // ------ Initial page load ------
        loadPage(currentPage);
        setSelected();
        reader.focus();

        // ------ Responsive: reload on resize to adjust mode ------
        window.addEventListener('resize', function() {
            window.location.reload();
        });
    </script>
</body>
</html>