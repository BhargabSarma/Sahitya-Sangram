<div class="shelf-container">
    <div class="shelf-collections-title">
        <strong>OUR BEST COLLECTIONS</strong>
    </div>
    <div class="shelf-main-content">
        <div class="shelf-books-wrapper">
            @foreach($books as $index => $book)
            <a href="{{ route('books.show', $book->id) }}" class="shelf-books__item" style="text-decoration:none;color:inherit;">
                <div class="shelf-books__container">
                    <div class="shelf-books__cover">
                        <div class="shelf-books__back-cover"></div>
                        <div class="shelf-books__inside">
                            <div class="shelf-books__page"></div>
                            <div class="shelf-books__page"></div>
                            <div class="shelf-books__page"></div>
                        </div>
                        <div class="shelf-books__image">
                            <img src="{{ asset('storage/' . $book->cover_image_front) }}" alt="{{ $book->title }}">
                            <div class="shelf-books__effect"></div>
                            <div class="shelf-books__light"></div>
                        </div>
                        <div class="shelf-books__hitbox" data-book-index="{{ $index }}"></div>
                    </div>
                </div>
                <div class="shelf-books__title">
                    {{ $book->title }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="shelf-view-all-row">
        <a href="{{ route('books.bookshelf') }}" class="cssbuttons-io-button" style="display: inline-flex; align-items: center; text-decoration: none;">
            View All
            <span class="icon">
                <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
                </svg>
            </span>
        </a>
    </div>
</div>
<!-- Spacer between shelf and plans for mobile and desktop -->
<div class="mobile-gap"></div>