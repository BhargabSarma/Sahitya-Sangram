<div class="shelf-container">
  <div class="shelf-collections-title">
    <strong>OUR COLLECTIONS</strong>
  </div>
  <!-- Main Content Row -->
  <div class="shelf-main-content">
    <!-- Books Section (Centered, now full width) -->
    <div class="shelf-books-wrapper" style="margin-left:auto;margin-right:auto;">
      <!-- Book 1 -->
      <a href="{{ route('books.show', 1) }}" class="shelf-books__item" style="text-decoration:none;color:inherit;">
        <div class="shelf-books__container">
          <div class="shelf-books__cover">
            <div class="shelf-books__back-cover"></div>
            <div class="shelf-books__inside">
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
            </div>
            <div class="shelf-books__image">
              @if($book->cover_image_front)
          <img src="{{  asset($book->cover_image_front }}" alt="Front Cover">
        @else
          <img src="{{ asset('images/default_cover.jpg') }}" alt="No Cover">
        @endif
              <div class="shelf-books__effect"></div>
              <div class="shelf-books__light"></div>
            </div>
            <div class="shelf-books__hitbox" data-book-index="0"></div>
          </div>
        </div>
        <div class="shelf-books__title">
          Creative Act<br>
          Rick Rubin
        </div>
      </a>
      <!-- Book 2 -->
      <a href="{{ route('books.show', 2) }}" class="shelf-books__item" style="text-decoration:none;color:inherit;">
        <div class="shelf-books__container">
          <div class="shelf-books__cover">
            <div class="shelf-books__back-cover"></div>
            <div class="shelf-books__inside">
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
            </div>
            <div class="shelf-books__image">
              @if($book->cover_image_front)
          <img src="{{  asset($book->cover_image_front }}" alt="Front Cover">
        @else
          <img src="{{ asset('images/default_cover.jpg') }}" alt="No Cover">
        @endif
              <div class="shelf-books__effect"></div>
              <div class="shelf-books__light"></div>
            </div>
            <div class="shelf-books__hitbox" data-book-index="1"></div>
          </div>
        </div>
        <div class="shelf-books__title">
          Psychedelics and Mental Health<br>
          Irene de Caso
        </div>
      </a>
      <!-- Book 3 -->
      <a href="{{ route('books.show', 3) }}" class="shelf-books__item" style="text-decoration:none;color:inherit;">
        <div class="shelf-books__container">
          <div class="shelf-books__cover">
            <div class="shelf-books__back-cover"></div>
            <div class="shelf-books__inside">
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
            </div>
            <div class="shelf-books__image">
              @if($book->cover_image_front)
          <img src="{{  asset($book->cover_image_front }}" alt="Front Cover">
        @else
          <img src="{{ asset('images/default_cover.jpg') }}" alt="No Cover">
        @endif
              <div class="shelf-books__effect"></div>
              <div class="shelf-books__light"></div>
            </div>
            <div class="shelf-books__hitbox" data-book-index="2"></div>
          </div>
        </div>
        <div class="shelf-books__title">
          The Power of Now<br>
          Eckhart Tolle
        </div>
      </a>
      <!-- Book 4 -->
      <a href="{{ route('books.show', 4) }}" class="shelf-books__item" style="text-decoration:none;color:inherit;">
        <div class="shelf-books__container">
          <div class="shelf-books__cover">
            <div class="shelf-books__back-cover"></div>
            <div class="shelf-books__inside">
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
              <div class="shelf-books__page"></div>
            </div>
            <div class="shelf-books__image">
              @if($book->cover_image_front)
          <img src="{{ asset($book->cover_image_front }}" alt="Front Cover">
        @else
          <img src="{{ asset('images/default_cover.jpg') }}" alt="No Cover">
        @endif
              <div class="shelf-books__effect"></div>
              <div class="shelf-books__light"></div>
            </div>
            <div class="shelf-books__hitbox" data-book-index="3"></div>
          </div>
        </div>
        <div class="shelf-books__title">
          Entering the Way of the Bodhisattva<br>
          Shantideva
        </div>
      </a>
    </div>
  </div>
  <!-- Button right after main content, inside .shelf-container grid -->
  <div class="shelf-view-all-row">
    <a href="{{ route('books.bookshelf') }}" class="cssbuttons-io-button"
      style="display: inline-flex; align-items: center; text-decoration: none;">
      View All
      <span class="icon">
        <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 0h24v24H0z" fill="none"></path>
          <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
            fill="currentColor"></path>
        </svg>
      </span>
    </a>
  </div>
</div>