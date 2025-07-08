<div class="mt-12">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Suggested Books</h2>
    <div class="flex gap-6 overflow-x-auto pb-2">
        @foreach($suggestedBooks as $suggested)
            <a href="{{ route('book.details', $suggested['id']) }}"
               class="flex-shrink-0 w-32 group"
               title="{{ $suggested['title'] }}">
                <div class="rounded-lg overflow-hidden shadow-md bg-gray-100 mb-2 transition-transform duration-200 group-hover:scale-105 group-hover:shadow-lg">
                    <img src="{{ $suggested['cover_url'] ?? 'https://via.placeholder.com/150x220?text=No+Cover' }}"
                         alt="{{ $suggested['title'] }}"
                         class="w-full h-44 object-cover" />
                </div>
                <div class="text-xs font-semibold text-gray-700 truncate text-center">
                    {{ $suggested['title'] }}
                </div>
            </a>
        @endforeach
    </div>
</div>