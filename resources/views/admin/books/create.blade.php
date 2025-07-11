@extends('layouts.admin')

@section('title', 'Add Book')

@section('content')
    <div class="container">
        <h2>Add New Book</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form id="book-upload-form" action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @if(old('category') === $cat) selected @endif>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Number of Pages</label>
                <input type="number" name="number_of_pages" class="form-control" value="{{ old('number_of_pages') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Front Cover Image</label>
                <input type="file" name="cover_image_front" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Back Cover Image</label>
                <input type="file" name="cover_image_back" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Language</label>
                <input type="text" name="language" class="form-control" value="{{ old('language') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Level *</label>
                <select name="level" class="form-select" required>
                    <option value="Beginner" @if(old('level') === 'Beginner') selected @endif>Beginner</option>
                    <option value="Intermediate" @if(old('level') === 'Intermediate') selected @endif>Intermediate</option>
                    <option value="Advanced" @if(old('level') === 'Advanced') selected @endif>Advanced</option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_bestseller" class="form-check-input" id="is_bestseller" value="1"
                    @if(old('is_bestseller')) checked @endif>
                <label class="form-check-label" for="is_bestseller">Bestseller</label>
            </div>
            <div class="mb-3">
                <label class="form-label">Hard Copy Price *</label>
                <input type="number" name="hard_copy_price" class="form-control" step="0.01" min="0"
                    value="{{ old('hard_copy_price') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Digital Price *</label>
                <input type="number" name="digital_price" class="form-control" step="0.01" min="0"
                    value="{{ old('digital_price') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Author *</label>
                <select name="author_id" class="form-select" required>
                    <option value="">Select Author</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @if(old('author_id') == $author->id) selected @endif>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Book File (PDF/EPUB) *</label>
                <input type="file" name="book_file" class="form-control" accept=".pdf,.epub" required>
                @error('book_file')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" id="submit-btn">Add Book</button>
            <div id="ready-status" style="display:none; margin-top:10px;">
                <span id="book-process-status">
                    @if(isset($book) && !$book->is_ready)
                        Processing book file, please wait...
                    @elseif(isset($book) && $book->is_ready)
                        Book Ready!
                    @else
                        Checking status...
                    @endif
                </span>
            </div>
        </form>
        <script>
            document.getElementById('book-upload-form').addEventListener('submit', function (e) {
                document.getElementById('ready-status').style.display = 'block';
                document.getElementById('book-process-status').innerText = 'Processing book file, please wait...';
                document.getElementById('submit-btn').disabled = true;
            });

            @if(isset($book))
                function pollCheckReady(bookId) {
                    fetch(`/books/${bookId}/check_ready`)
                        .then(response => response.json())
                        .then(data => {
                            const statusSpan = document.getElementById('book-process-status');
                            if (statusSpan) {
                                if (data.ready) {
                                    statusSpan.innerText = 'Book Ready!';
                                    statusSpan.classList.add('text-success');
                                    document.getElementById('submit-btn').disabled = false;
                                } else if (data.failed) {
                                    statusSpan.innerText = 'Book processing failed!';
                                    statusSpan.classList.add('text-danger');
                                    document.getElementById('submit-btn').disabled = false;
                                } else {
                                    statusSpan.innerText = 'Processing book file, please wait...';
                                    setTimeout(() => pollCheckReady(bookId), 3000);
                                }
                            }
                        })
                        .catch(() => {
                            const statusSpan = document.getElementById('book-process-status');
                            if (statusSpan) {
                                statusSpan.innerText = 'Error checking status.';
                                statusSpan.classList.add('text-danger');
                            }
                        });
                }
                pollCheckReady({{ $book->id }});
            @endif
        </script>
    </div>
@endsection