{{-- filepath: resources/views/errors/post_too_large.blade.php --}}
@extends('layouts.app')

@section('title', 'Upload Too Large')

@section('content')
    <div class="alert alert-danger">
        The uploaded file is too large. Please upload a smaller file.
    </div>
@endsection