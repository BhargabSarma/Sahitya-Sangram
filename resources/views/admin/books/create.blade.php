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
                <div class="d-flex align-items-center mb-2">
                    <div id="spinner" class="spinner-border text-primary me-2" style="display:none;" role="status">
                        <span class="visually-hidden">Processing...</span>
                    </div>
                    <span id="book-process-status" class="fw-bold"></span>
                </div>
                <div class="progress" style="height: 20px; display:none;" id="book-process-progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="book-process-progress-bar"
                        role="progressbar" style="width: 0%">0%</div>
                </div>
            </div>
        </form>
        <script>
            document.getElementById('book-upload-form').addEventListener('submit', function (e) {
                document.getElementById('ready-status').style.display = 'block';
                document.getElementById('book-process-status').innerText = 'Processing book file, please wait...';
                document.getElementById('book-process-status').className = 'fw-bold text-warning';
                document.getElementById('submit-btn').disabled = true;
                document.getElementById('spinner').style.display = '';
                document.getElementById('book-process-progress').style.display = 'none';
            });

            @if(isset($book))
                function pollCheckReady(bookId) {
                    fetch(`/books/${bookId}/check_ready`)
                        .then(response => response.json())
                        .then(data => {
                            const statusSpan = document.getElementById('book-process-status');
                            const spinner = document.getElementById('spinner');
                            const progressBar = document.getElementById('book-process-progress-bar');
                            const progressDiv = document.getElementById('book-process-progress');
                            statusSpan.className = 'fw-bold';

                            // Show progress bar and % if available
                            if (typeof data.progress !== "undefined" && data.status === "processing") {
                                progressDiv.style.display = '';
                                progressBar.style.width = data.progress + '%';
                                progressBar.innerText = data.progress + '%';
                            } else {
                                progressDiv.style.display = 'none';
                            }

                            if (data.status === 'completed' || data.ready) {
                                statusSpan.innerText = 'Book Ready!';
                                statusSpan.classList.add('text-success');
                                spinner.style.display = 'none';
                                document.getElementById('submit-btn').disabled = false;
                                progressBar.style.width = '100%';
                                progressBar.innerText = '100%';
                                progressDiv.style.display = '';
                            } else if (data.status === 'failed') {
                                statusSpan.innerText = 'Book processing failed: ' + (data.error ? data.error : '');
                                statusSpan.classList.add('text-danger');
                                spinner.style.display = 'none';
                                document.getElementById('submit-btn').disabled = false;
                                progressDiv.style.display = 'none';
                            } else if (data.status === 'processing') {
                                statusSpan.innerText = 'Processing book file: ' + (data.progress || 0) + '% completed...';
                                statusSpan.classList.add('text-warning');
                                spinner.style.display = '';
                                document.getElementById('submit-btn').disabled = true;
                                setTimeout(() => pollCheckReady(bookId), 3000);
                            } else if (data.status === 'pending') {
                                statusSpan.innerText = 'Waiting for processing to start...';
                                statusSpan.classList.add('text-warning');
                                spinner.style.display = '';
                                document.getElementById('submit-btn').disabled = true;
                                progressDiv.style.display = 'none';
                                setTimeout(() => pollCheckReady(bookId), 3000);
                            } else {
                                statusSpan.innerText = 'Checking status...';
                                statusSpan.classList.add('text-warning');
                                spinner.style.display = '';
                                document.getElementById('submit-btn').disabled = true;
                                progressDiv.style.display = 'none';
                                setTimeout(() => pollCheckReady(bookId), 3000);
                            }
                        })
                        .catch(() => {
                            const statusSpan = document.getElementById('book-process-status');
                            statusSpan.innerText = 'Error checking status.';
                            statusSpan.className = 'fw-bold text-danger';
                            document.getElementById('spinner').style.display = 'none';
                            document.getElementById('book-process-progress').style.display = 'none';
                        });
                }
                pollCheckReady({{ $book->id }});
            @endif
        </script>
    </div>
@endsection