@extends('layouts.admin')

@section('title', 'Add Author')

@push('styles')
{{-- 
  This custom CSS provides the modern and minimal styling for the form.
  It's placed here to be self-contained, but for larger applications,
  you would typically move this to a dedicated CSS file (e.g., public/css/forms.css)
  and link it in your main layout file.
--}}
<style>
    .form-wrapper {
        font-family: 'Inter', sans-serif;
    }

    .form-container {
        background-color: #ffffff;
        margin: auto;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 650px; /* Increased width for better spacing */
    }

    .form-container h2 {
        margin-top: 0;
        margin-bottom: 2rem;
        font-size: 1.75rem;
        font-weight: 600;
        text-align: center;
        color: #2d3748;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4a5568;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #2d3748;
        background-color: #fdfdfe;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }
    
    .form-control.is-invalid {
        border-color: #e53e3e; /* Red border for errors */
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .file-input-wrapper {
        position: relative;
        cursor: pointer;
    }

    .file-input-label {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #718096;
        background-color: #fdfdfe;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: border-color 0.2s;
    }

    .file-input-wrapper:hover .file-input-label {
        border-color: #a0aec0;
    }
    
    .file-input-wrapper .is-invalid {
        border-color: #e53e3e;
    }

    .file-input-native {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
    }
    
    .file-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #718096;
    }

    .btn-submit {
        width: 100%;
        padding: 0.875rem;
        font-size: 1rem;
        font-weight: 600;
        color: #ffffff;
        background-color: #4a90e2;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #357ABD;
    }
    
    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="form-wrapper">
    <div class="form-container">
        <h2>Add New Author</h2>

        <form method="POST" action="{{ route('authors.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Bio Field -->
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea 
                    id="bio" 
                    name="bio" 
                    class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>
                @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Custom Photo Field -->
            <div class="form-group">
                <label for="photo">Photo</label>
                <div class="file-input-wrapper">
                    <label for="photo" class="file-input-label @error('photo') is-invalid @enderror">
                         <span class="file-name">Choose a file...</span>
                    </label>
                    <input 
                        type="file" 
                        id="photo" 
                        name="photo" 
                        class="file-input-native" 
                        accept="image/*">
                </div>
                 @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn-submit" type="submit">Add Author</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // JavaScript to display the selected file name in the custom file input
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('photo');
        const fileNameSpan = document.querySelector('.file-name');

        if (fileInput) {
            fileInput.addEventListener('change', (event) => {
                const files = event.target.files;
                if (files.length > 0) {
                    fileNameSpan.textContent = files[0].name;
                    fileNameSpan.style.color = '#2d3748';
                } else {
                    fileNameSpan.textContent = 'Choose a file...';
                    fileNameSpan.style.color = '#718096';
                }
            });
        }
    });
</script>
@endpush
