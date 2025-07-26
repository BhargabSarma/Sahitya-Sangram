@extends('layouts.admin')

@section('title', 'Import Book from FTP')

@section('content')
    <div class="container">
        <h2>Import Book from FTP Uploads</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.importFromFtp.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select PDF File from FTP Uploads *</label>
                <select name="pdf_file" class="form-select" required>
                    <option value="">-- Select PDF --</option>
                    @foreach($files as $file)
                        <option value="{{ $file }}">{{ $file }}</option>
                    @endforeach
                </select>
            </div>
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
            <!-- No book file upload field: file will be imported from FTP -->
            <button type="submit" class="btn btn-primary">Import Book</button>
        </form>
        <div class="alert alert-info mt-3">
            Select a PDF you uploaded via FTP. After import, you can start PDF-to-image conversion from the Admin Dashboard.
        </div>
    </div>
@endsection