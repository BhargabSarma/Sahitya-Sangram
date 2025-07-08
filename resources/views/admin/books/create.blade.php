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
        </form>
        @if(isset($book))
            <script>
                function pollCheckReady(bookId) {
                    fetch(`/books/${bookId}/check_ready`)
                        .then(response => response.json())
                        .then(data => {
                            const statusSpan = document.getElementById('book-process-status');
                            if (statusSpan) {
                                statusSpan.innerText = data.ready ? 'Book Ready!' : 'Processing...';
                            }
                            if (!data.ready) {
                                setTimeout(() => pollCheckReady(bookId), 3000);
                            }
                        });
                }
                document.addEventListener('DOMContentLoaded', function () {
                    pollCheckReady({{ $book->id }});
                });
            </script>
        @endif
        <span id="ready-status">Checking status...</span>
    </div>
@endsection{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">
    @include('admin.components.sidebar')
    <div class="flex-1 ml-64">
        <div class="container mx-auto px-4 py-8">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-lg shadow-md p-8 max-w-4xl mx-auto">
                @csrf
                <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 20h9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 20H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2h-2" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 17v-6h6v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Add New Book
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Book Info -->
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4">General Information</h3>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Book Title</label>
                                <input type="text" name="title"
                                    class="w-full border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Subtitle</label>
                                <input type="text" name="subtitle" class="w-full border rounded-md px-4 py-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Category</label>
                                <select name="category" class="w-full border rounded-md px-4 py-2" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Description</label>
                                <textarea name="description" class="w-full border rounded-md px-4 py-2 h-24 resize-none"
                                    required></textarea>
                            </div>
                            <div class="mb-4 flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-gray-600 mb-1">No. of Pages</label>
                                    <input type="number" name="pages" class="w-full border rounded-md px-4 py-2" min="1"
                                        required>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-gray-600 mb-1">Level</label>
                                    <div class="flex gap-2 mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="level" value="Beginner" class="form-radio"
                                                required>
                                            <span class="ml-1">Beginner</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="level" value="Intermediate" class="form-radio">
                                            <span class="ml-1">Intermediate</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="level" value="Advance" class="form-radio">
                                            <span class="ml-1">Advance</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Languages (comma separated)</label>
                                <input type="text" name="languages" class="w-full border rounded-md px-4 py-2"
                                    placeholder="e.g. English, French, Spanish">
                            </div>
                            <div class="mb-4 flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-gray-600 mb-1">Hard Copy Price (₹)</label>
                                    <input type="number" name="hard_copy_price"
                                        class="w-full border rounded-md px-4 py-2" min="0" step="0.01" required>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-gray-600 mb-1">Digital Price (₹)</label>
                                    <input type="number" name="digital_price" class="w-full border rounded-md px-4 py-2"
                                        min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Upload Images -->
                    <div>
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="font-semibold text-lg mb-4">Upload Cover</h3>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Front Cover</label>
                                <input type="file" name="cover_front" accept="image/*"
                                    class="block w-full text-sm text-gray-500" required
                                    onchange="previewImage(event, 'front-preview')">
                                <div class="mt-3 flex justify-center">
                                    <img id="front-preview" src="" alt="Front Cover Preview"
                                        class="rounded-lg shadow w-64 h-80 object-cover hidden border-2 border-indigo-200" />
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-600 mb-1">Back Cover</label>
                                <input type="file" name="cover_back" accept="image/*"
                                    class="block w-full text-sm text-gray-500"
                                    onchange="previewImage(event, 'back-preview')">
                                <div class="mt-3 flex justify-center">
                                    <img id="back-preview" src="" alt="Back Cover Preview"
                                        class="rounded-lg shadow w-64 h-80 object-cover hidden border-2 border-indigo-200" />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4">Upload Full Book</h3>
                            <input type="file" name="book_file" accept=".pdf,.epub,.doc,.docx"
                                class="block w-full text-sm text-gray-500" required>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button type="button"
                        class="px-6 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200 border border-gray-200">Save
                        Draft</button>
                    <button type="submit"
                        class="px-8 py-2 bg-green-400 hover:bg-green-500 rounded-md text-white font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Confirm & Add Book
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgElement = document.getElementById(previewId);
                imgElement.src = e.target.result;
                imgElement.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>

</html> --}}