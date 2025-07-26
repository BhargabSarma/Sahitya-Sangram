<div class="covers-grid">
    @forelse($books as $book)
        <a href="{{ route('books.show', $book->id) }}" class="cover-card" title="{{ $book->title }}">
            @if($book->cover_image_front)
                <img src="{{ asset('storage/' . $book->cover_image_front) }}" class="card-img-top" alt="Cover">
            @else
                <img src="{{ asset('images/default_cover.jpg') }}" class="card-img-top" alt="No Cover">
            @endif
        </a>
    @empty
        <div class="col-span-full text-center text-gray-500">No books found.</div>
    @endforelse
</div>